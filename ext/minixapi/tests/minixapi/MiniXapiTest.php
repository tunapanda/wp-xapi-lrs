<?php

require_once "PHPUnit/Framework/TestCase.php";
require_once __DIR__."/../../MiniXapi.php";

class MiniXapiTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		if (file_exists(__DIR__."/../data/minixapitest.sqlite"))
			unlink(__DIR__."/../data/minixapitest.sqlite");

		if (file_exists(__DIR__."/../data/minixapitest.sqlite"))
			throw new Exception("can't delete file");

		if (!is_dir(__DIR__."/../data"))
			mkdir(__DIR__."/../data");

		$this->miniXapi=new MiniXapi();
		$this->miniXapi->setDsn("sqlite:".__DIR__."/../data/minixapitest.sqlite");
	}

	/**
	 * @expectedException Exception
	 */
	function testNoDsnInstall() {

		// We should trhow an error if the DSN is not set.
		$miniXapi=new MiniXapi();
		$miniXapi->install();
	}

	/**
	 * Test installation
	 */
	function testInstall() {
		$this->assertFalse(file_exists(__DIR__."/../data/minixapitest.sqlite"));
		$this->miniXapi->install();
		$this->assertTrue(file_exists(__DIR__."/../data/minixapitest.sqlite"));
	}

	/**
	 * Test uninstall
	 */
	function testUninstall() {
		$this->assertFalse($this->miniXapi->isInstalled());
		$this->miniXapi->install();
		$this->assertTrue($this->miniXapi->isInstalled());
		$this->miniXapi->uninstall();
		$this->assertFalse($this->miniXapi->isInstalled());
	}

	/**
	 * Test put and get statements.
	 */
	function testGetPut() {
		$this->miniXapi->install();

		$statement=<<<__END__
{
  "actor": {
    "name": "Sally Glider",
    "mbox": "mailto:sally@example.com"
  },
  "verb": {
    "id": "http://adlnet.gov/expapi/verbs/experienced",
    "display": { "en-US": "experienced" }
  },
  "object": {
    "id": "http://example.com/activities/solo-hang-gliding",
    "definition": {
      "name": { "en-US": "Solo Hang Gliding" }
    }
  }
}
__END__;

		$res=$this->miniXapi->processRequest("POST","statements",array(),$statement);
		$this->assertEquals(sizeof($res),1);
		$this->assertEquals(strlen($res[0]),36);

		$res=$this->miniXapi->processRequest("GET","statements");
		$this->assertEquals(sizeof($res["statements"]),1);
		$this->assertEquals($res["statements"][0]["actor"]["name"],"Sally Glider");
		$id=$res["statements"][0]["id"];

		$res=$this->miniXapi->processRequest("GET","statements",array("statementId"=>$id));
		$this->assertEquals($res["actor"]["name"],"Sally Glider");
	}

	function testGetWithVerb() {
		$this->miniXapi->install();

		$statements=array(
			array(
				"actor"=>array("mbox"=>"mailto:sally@example.com"),
				"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/experienced"),
				"object"=>array("id"=>"http://example.com/activities/solo-hang-gliding")
			),
			array(
				"actor"=>array("mbox"=>"mailto:alice@example.com"),
				"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/completed"),
				"object"=>array("id"=>"http://example.com/activities/solo-hang-gliding")
			),
			array(
				"actor"=>array("mbox"=>"mailto:bob@example.com"),
				"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/experienced"),
				"object"=>array("id"=>"http://example.com/activities/solo-hang-gliding")
			),
			array(
				"actor"=>array("mbox"=>"mailto:cesar@example.com"),
				"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/experienced"),
				"object"=>array("id"=>"http://example.com/activities/solo-hang-gliding")
			),
			array(
				"actor"=>array("mbox"=>"mailto:david@example.com"),
				"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/completed"),
				"object"=>array("id"=>"http://example.com/activities/touch-typing")
			),
			array(
				"actor"=>array("mbox"=>"mailto:eric@example.com"),
				"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/experienced"),
				"object"=>array("id"=>"http://example.com/activities/touch-typing")
			),
		);

		foreach ($statements as $statement)
			$this->miniXapi->putStatement($statement);

		$res=$this->miniXapi->getStatements(
			array("verb"=>"http://adlnet.gov/expapi/verbs/experienced")
		);
		$this->assertCount(4,$res);

		$res=$this->miniXapi->processRequest("GET","statements",
			array("activity"=>"http://example.com/activities/solo-hang-gliding")
		);
		$this->assertCount(4,$res["statements"]);

		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"verb"=>"http://adlnet.gov/expapi/verbs/experienced",
				"activity"=>"http://example.com/activities/touch-typing"
			)
		);
		$this->assertCount(1,$res["statements"]);

		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"agent"=>json_encode(array("mbox"=>"mailto:david@example.com")),
				"verb"=>"http://adlnet.gov/expapi/verbs/completed",
				"activity"=>"http://example.com/activities/touch-typing"
			)
		);
		$this->assertCount(1,$res["statements"]);

		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"agent"=>json_encode(array("mbox"=>"mailto:eric@example.com")),
				"verb"=>"http://adlnet.gov/expapi/verbs/completed",
				"activity"=>"http://example.com/activities/touch-typing"
			)
		);
		$this->assertCount(0,$res["statements"]);
	}

	/**
	 * @expectedException Exception
	 */
	function testMboxFail() {
		$this->miniXapi->install();

		$statement=array(
			"actor"=>array("mbox"=>"mailto:eric@example.com"),
			"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/experienced"),
			"object"=>array("id"=>"http://example.com/activities/touch-typing")
		);

		$this->miniXapi->processRequest("POST","statements",array(),json_encode($statement));

		// The agent needs to be a json object!
		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"agent"=>"mailto:eric@example.com",
			)
		);
	}

	function testMbox() {
		$this->miniXapi->install();

		$statement=array(
			"actor"=>array("mbox"=>"mailto:eric@example.com"),
			"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/experienced"),
			"object"=>array("id"=>"http://example.com/activities/touch-typing")
		);

		$this->miniXapi->processRequest("POST","statements",array(),json_encode($statement));

		// The agent needs to be a json object!
		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"agent"=>json_encode(array("mbox"=>"mailto:eric@example.com")),
			)
		);

		$this->assertCount(1,$res["statements"]);
	}

	function testContext() {
		$this->miniXapi->install();
		$statement=array(
			"actor"=>array("mbox"=>"mailto:alice@example.com"),
			"verb"=>array("id"=>"http://adlnet.gov/expapi/verbs/completed"),
			"object"=>array("id"=>"http://example.com/activities/solo-hang-gliding"),
			"context"=>array(
				"contextActivities"=>array(
					"category"=>array(
						array(
							"objectType"=>"Activity",
							"id"=>"http://swag.tunapanda.org/"
						)
					)
				)
			)
		);

		$this->miniXapi->processRequest("POST","statements",array(),json_encode($statement));

		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"activity"=>"http://swag.tunapanda.org/"
			)
		);
		$this->assertCount(0,$res["statements"]);

		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"activity"=>"http://swag.tunapanda.org/",
				"related_activities"=>TRUE
			)
		);
		$this->assertCount(1,$res["statements"]);

		$res=$this->miniXapi->processRequest("GET","statements",
			array(
				"activity"=>"http://example.com/activities/solo-hang-gliding",
				"related_activities"=>TRUE
			)
		);
		$this->assertCount(1,$res["statements"]);
	}
}