<?php

	require_once __DIR__."/../../ext/TinCanPHP/autoload.php";

		$statementSource=<<<__END__
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

	$statement=TinCan\Statement::fromJSON($statementSource);

	if (!$statement->hasId())
		$statement->setId(TinCan\Util::getUUID());

	if (!$statement->getTimestamp())
		$statement->setTimestamp(TinCan\Util::getTimestamp());

	$statement->setStored(TinCan\Util::getTimestamp());

	if (!$statement->getTarget())
		echo "error...";

/*	$statement->stamp();
	$statement->setStored(new DateTime());*/
	echo json_encode($statement->asVersion(TinCan\Version::latest()),JSON_PRETTY_PRINT)."\n";

/*	$res=$statement->verify();

	print_r($res);*/

//	$statement->stamp();
	//print_r($statement);

//	print_r($statement->asVersion("hello"))