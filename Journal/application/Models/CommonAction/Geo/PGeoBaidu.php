<?php
class Models_CommonAction_Geo_PGeoBaidu extends Models_CommonAction_Geo_PGeographic
{
	public function __construct()
	{
		throw new Exception("PGeoBaidu is not on service now");
	}
	/* (non-PHPdoc)
	 * @see Models_CommonAction_Geo_PGeographic::getCurAddress()
	 */
	public function getCurAddress($latitude, $longitude, $page, $rowCount) {
		// TODO Auto-generated method stub
		throw new Exception('I am empty now');
		if (!parent::canSearch())
		{
			return false;
		}
		
	}

	/* (non-PHPdoc)
	 * @see Models_CommonAction_Geo_PGeographic::searchInArea()
	 */
	public function searchInArea($keyword, $area, $page, $rowCount) {
		// TODO Auto-generated method stub
		throw new Exception('I am empty now');
		if (!parent::canSearch())
		{
			return false;
		}
	}

	/* (non-PHPdoc)
	 * @see Models_CommonAction_Geo_PGeographic::searchNearBy()
	 */
	public function searchNearBy($keyword, $latitude, $longitude, $radius, $page, $rowCount) {
		// TODO Auto-generated method stub
		throw new Exception('I am empty now');
		if (! parent::canSearch()) {
            return false;
        }
	}


}