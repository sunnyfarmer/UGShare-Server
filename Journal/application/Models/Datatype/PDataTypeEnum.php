<?php
/**
 * @author samson
 * @version 1.0
 * @created 01-一月-2011 10:08:29
 */
class Models_Datatype_PDataTypeEnum
{
	//每个属性最多能拥有这么多的比较方式
	const POWER = 100;
	
	const PDATATYPEMIN = 0;
	const PDATATYPEINTEGER = 1;
	const PDATATYPEFLOAT = 2;
	const PDATATYPETEXT = 3;
	const PDATATYPEPOSITION = 4;
	const PDATATYPETIME = 5;
	const PDATATYPEPICTURE = 6;
	const PDATATYPEVIDEO = 7;
	const PDATATYPEMUSIC = 8;
	//...
	const PDATATYPEMAX = 32;
	static $PROPERTYTYPE_ARRAY = array(
		self::PDATATYPEMIN=>'PDATATYPE_MIN_LIMIT',
		
		self::PDATATYPEINTEGER=>'Models_Datatype_PDTInteger',
		self::PDATATYPEFLOAT=>'Models_Datatype_PDTFloat',
		self::PDATATYPETEXT=>'Models_Datatype_PDTText',
		self::PDATATYPEPOSITION=>'Models_Datatype_PDTPosition',
		self::PDATATYPETIME=>'Models_Datatype_PDTTime',
		self::PDATATYPEPICTURE=>'Models_Datatype_PDTPicture',
		self::PDATATYPEVIDEO=>'Models_Datatype_PDTVideo',
		self::PDATATYPEMUSIC=>'Models_Datatype_PDTMusic',
		//...
		self::PDATATYPEMAX=>'PDATATYPE_MAX_LIMIT'
	);
	
	const PECOMPARISION_MIN = 0;
	
	const PECOMPARISION_INTEGER_BIGGER = 101;
	const PECOMPARISION_INTEGER_SMALLER = 102;
	
	const PECOMPARISION_FLOAT_BIGGER = 201;
	const PECOMPARISION_FLOAT_SMALLER = 202;
	
	const PECOMPARISION_TEXT_LONGER = 301;
	const PECOMPARISION_TEXT_SHORTER = 302;
	
	const PECOMPARISION_POSITION_EASTER = 401;
	const PECOMPARISION_POSITION_WESTER = 402;
	const PECOMPARISION_POSITION_SOUTHER = 403;
	const PECOMPARISION_POSITION_NORTHER = 404;
	//...
	const PECOMPARISION_MAX = 3200;
	
	static $COMPARISION_ARRAY = array(
		self::PECOMPARISION_MIN=>'MIN',
		
		self::PECOMPARISION_INTEGER_BIGGER=>'biggerThan',
		self::PECOMPARISION_INTEGER_SMALLER=>'smallerThan',

		self::PECOMPARISION_FLOAT_BIGGER=>'biggerThan',
		self::PECOMPARISION_FLOAT_SMALLER=>'smallerThan',
		
		self::PECOMPARISION_TEXT_LONGER=>'longerThan',
		self::PECOMPARISION_TEXT_SHORTER=>'shorterThan',		
		
		self::PECOMPARISION_POSITION_EASTER=>'easterThan',
		self::PECOMPARISION_POSITION_WESTER=>'westerThan',
		self::PECOMPARISION_POSITION_SOUTHER=>'southerThan',
		self::PECOMPARISION_POSITION_NORTHER=>'northerThan',
		//...
		self::PECOMPARISION_MAX=>'MAX'
	);
	
}


