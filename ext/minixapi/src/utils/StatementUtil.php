<?php

/**
 * Statement util.
 */
class StatementUtil {

	/**
	 * Ensure the statement has everything needed to
	 * be valid.
	 */
	public static function formalize($statement) {
		if (!$statement->hasId())
			$statement->setId(TinCan\Util::getUUID());

		if (!$statement->getTimestamp())
			$statement->setTimestamp(TinCan\Util::getTimestamp());

		$statement->setStored(TinCan\Util::getTimestamp());

		if (!$statement->getActor())
			throw new Exception("The statement does not have an actor");

		if (!$statement->getVerb())
			throw new Exception("The statement does not have a verb");

		if (!$statement->getTarget())
			throw new Exception("The statement does not have a target");
	}
}