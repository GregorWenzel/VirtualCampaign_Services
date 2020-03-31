<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		production.class.php
 * 
 * @author  	Christiane Kuhn <kuhn@webdevelopment-koeln.de>
 * @link 		http://webdevelopment-koeln.de
 * 
 * @category	Virtual Campaign 
 *
 * 
 * This Script was developed by Christiane Kuhn - ChK Web-Development.
 * You are authorised to use this Script on your internet sides.
 * The right to use applies only to the direct use of the software by HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 * You are not authorised to resale, to lease or to publish the software outside of HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 *
 * The passing of this script is not allowed.
 *
 * Please do not remove this header.
 *
 * ----------------------------------------------------------------------------
 * Rev: 	2012-01 	ChK	Created
 * 
 * ----------------------------------------------------------------------------
 *
 */

/**
 * class production
 *
 */
class production
{
	protected $_dbconn;
	public $_production_id;
	public $_account_id;
	public $_job_id;
	public $_film_id;
	public $_error;
	public $_errorArr;
	
	/**
	 * set required params for actions
	 * defined foreach action
	 */
    protected $_required = array(
        'createSingleProduction'     => array(
            array('name'     => 'user'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'abdicatives'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'indicatives'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'codecFormats'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '4096'
            )
        , array('name'       => 'email'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1000'
            )
        , array('name'       => 'durationTime'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '4096'
            )
        , array('name'       => 'jobName'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'motifIDs'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '4096'
            )
        , array('name'       => 'products'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '4096'
            )
        , array('name'       => 'template'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'audioParam'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'credit_cost'
            , 'type'         => 'decimal'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )

    , 'createProduct'                => array(
            array('name'     => 'name'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'is_dicative'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'id'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'description'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'location'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'previewFrame'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'credit_cost'
            , 'type'         => 'decimal'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'productGroupID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'productFormatIDList'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'productContentCanLoopList'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'productContentAcceptsFilmList'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'credit_cost'
            , 'type'         => 'decimal'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )
    , 'addSubProduct'         => array(
            array('name'     => 'masterProductID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'getContentTypeListByAccount'  => array(
            array('name'     => 'accountGroupID'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )

    , 'getShortProductListByAccount' => array(
            array('name'     => 'accountGroupID'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'abdicative'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'indicative'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )

    , 'getProductListByAccount'      => array(
            array('name'     => 'accountGroupID'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )

    , 'getDicativesByAccount'        => array(
            array('name'     => 'accountGroupID'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )

    , 'setProductOwnership'          => array(
            array('name'     => 'productID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'ownerIDList'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        )

    , 'getProductionListByAccount'   => array(
            array('name'     => 'accountID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'getJobsByProductionID'        => array(
            array('name'     => 'productionID'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
             , array('name'       => 'is_preview'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        )
    , 'updateProduction'             => array(
            array('name'     => 'productionID'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        ,array('name'     => 'rendermachine_id'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )			
        , array('name'       => 'status'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'updateTime'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'error_code'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'priority'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        )
    , 'updateProductionPriority'     => array(
            array('name'     => 'productionID'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'priority'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'updateJob'                    => array(
            array('name'     => 'jobID'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'updateTime'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'status'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'error_code'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'output_extension'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'reinder_id'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '255'
            )
        )
    , 'updateFilm'                   => array(
            array('name'     => 'productionID'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'updateTime'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'duration'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'size'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        )
    , 'getFilmsByAccount'            => array(
            array('name'     => 'accountID'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'min_date'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        )
    , 'deleteFilm'                   => array(
            array('name'     => 'filmID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'permanent'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'getAudioById'                 => array(
            array('name'     => 'audioID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'checkProductionStatus'        => array(
            array('name'     => 'filmIDs'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        )
    , 'getAudioList'                 => array(
            array('name'     => 'accountID'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )
    , 'checkProductID'               => array(
            array('name'     => 'newProductID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'deleteProduction'             => array(
            array('name'     => 'productionID'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'workOnProduct'                => array
        (array('name'        => 'commands'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '2048'
            )
        , array('name'       => 'productID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'productFrameCount'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'productPreviewFrame'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'productCategoryID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'productLocation'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'productName'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'productDescription'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'productType'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'codecType'
            , 'type'         => 'array'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'can_reformat'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'resolution'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )
    , 'workOnContentFormat'          => array(
            array('name'     => 'contentFormatName'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'contentFormatAspect'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'contentFormatID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'task'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        )
    , 'deleteByAdmin'                => array(
            array('name'     => 'itemID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'targetItem'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'permanent'
            , 'type'         => 'int'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => ''
            )
        )
    , 'getJobStatus'                 => array(
            array('name'     => 'JobID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'setJobStatus'                 => array(
            array('name'     => 'JobID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'StatusID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'setJobErrorStatus'            => array(
            array('name'     => 'JobID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'StatusID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        )
    , 'updateHistory'                => array(
            array('name'     => 'AccountID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'FilmID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'JobIDList'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '2048'
            )
        , array('name'       => 'MotifIDList'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '4096'
            )
        )
    , 'sendHeartbeat'                => array(
            array('name'     => 'MachineName'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'Message'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'CurrentTime'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
		, array('name'       => 'IsActive'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
		, array('name'       => 'LicenseKey'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '40'
            )			
        )		
    , 'getCodecByFilmId' => [
            [
                'name'         => 'filmID',
                'type'         => 'int',
                'musthave'     => 'required',
                'maxstringlen' => ''
            ],
        ]
    , 'migrateFilm'                  => array(
            array('name'     => 'accountID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'name'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'oldAccountID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'oldAccountGroupID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'oldID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'jobID'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'codecs'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'products'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'motifs'
            , 'type'         => 'string'
            , 'musthave'     => 'required'
            , 'maxstringlen' => '1024'
            )
        , array('name'       => 'frames'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'size'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'creationTime'
            , 'type'         => 'int'
            , 'musthave'     => 'required'
            , 'maxstringlen' => ''
            )
        , array('name'       => 'formats'
            , 'type'         => 'string'
            , 'musthave'     => 'optional'
            , 'maxstringlen' => '256'
            )
        )
    );
				
	/**
	 * Constructor
	 *
	 * @param obj $dbconn
	 * @param int $production_id = null
	 * 
	 */
	public function __construct($dbconn, $account_id, $production_id = null)
	{
		$this->_dbconn 			= $dbconn;
		$this->_account_id		= $account_id;
		$this->_production_id 	= $production_id;
	}

    public function getCodecs()
    {
        $sql = "SELECT * FROM codec_type WHERE 1";
        $result = [];

        $res = $this->_dbconn->query($sql);
        while ($ds = mysqli_fetch_array($res)) {
            $result[] = $this->serializeCodec($ds);
        }

        return $result;
    }

    public function getCodecByFilmId($valueArr)
    {
        if (format::check_params($valueArr, $this->_required['getCodecByFilmId']) === false) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        } else {
            $sql = "SELECT formats FROM film WHERE film_id = " . (int)$valueArr['filmID'];

            $res = $this->_dbconn->query($sql);
            $data = mysqli_fetch_array($res);
            if (!$data) {
                return [];
            }

            $formats = explode(',', $data['formats']);
            $formats[] = 0;
            $formatsString = implode(',', $formats);
            $sql = "SELECT * FROM codec_type WHERE codec_type_id IN ({$formatsString})";
            $result = [];

            $res = $this->_dbconn->query($sql);
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = $this->serializeCodec($ds);
            }

            return $result;
        }
    }
	
	public function getJobStatus($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['getJobStatus']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$sql = "SELECT * FROM job WHERE job_id = ".(int)$valueArr["JobID"];
			
			if ($res = $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getJobStatus'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function setJobStatus($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['setJobStatus']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$sql = "UPDATE job SET status = ".(int)$valueArr['StatusID']." WHERE job_id = ".(int)$valueArr["JobID"];
			
			if ($res = $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->setJobStatus'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function setJobErrorStatus($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['setJobStatus']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$sql = "UPDATE job SET status = 12, error_code = ".(int)$valueArr['StatusID']." WHERE job_id = ".(int)$valueArr["JobID"];
			
			if ($res = $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->setJobStatus'),$this->_dbconn->error);
				return false;
			}
		}
	}
		
	public function updateProductionPriority($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['updateProductionPriority']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null; 
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			$sql = "update production set"
				  ." priority = ".(int)$valueArr['priority']
				  .",update_ts = FROM_UNIXTIME(".time().")"
				  ." where production_id = ".(int)$valueArr['productionID'];
			
			if ($res =  $this->_dbconn->query($sql)) {
				return true;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateProductionPriority'),$this->_dbconn->error);
				return false;
			}
		}
	}				  
	
	public function updateProduction($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['updateProduction']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			$sql = "update production set";
			
			$buffer = array();
			if (isset($valueArr['status']))
				array_push($buffer, " status = ".(int)$valueArr['status']);

			if (isset($valueArr['priority']))
				array_push($buffer, " priority = ".(int)$valueArr['priority']);				
				
			if (isset($valueArr['error_code']))
				array_push($buffer, " error_code = ".(int)$valueArr['error_code']);
						
			$sql .= implode(",", $buffer);
			$sql .= ",update_ts = FROM_UNIXTIME(".$valueArr['updateTime'].")";
			$sql .= ",rendermachine_id = ".(int)$valueArr['rendermachine_id'];
			$sql .= " where production.production_id = ".(int)$valueArr['productionID'];

			if ($res =  $this->_dbconn->query($sql)) {
				return true;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateProduction'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function updateFilm($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['updateFilm']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			$sql = "update film set";
			
			$buffer = array();
			if (isset($valueArr['duration']))
				array_push($buffer, " duration = ".(int)$valueArr['duration']);
							
			if (isset($valueArr['size']))
				array_push($buffer, ' size = "'.$valueArr['size'].'"');
				
			$sql .= implode(",", $buffer);
			$sql .= ",update_ts = FROM_UNIXTIME(".$valueArr['updateTime'].")";
			$sql .= " where film.production_id = ".(int)$valueArr['productionID'];

			//echo "<SQL>".$sql."</SQL>";
			if ($res =  $this->_dbconn->query($sql)) {
				return true;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateFilm'),$this->_dbconn->error);
				return false;
			}
		}
	}	
	
	public function getAudioById($valueArr){
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['getAudioById']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;	
			$sql = "select audio.name, media_format.extension"
				 . " from audio, media_format"
				 . " where audio.audio_id = ".(int)$valueArr['audioID']
				 . " and delete_ts IS NULL"
				 . " and media_format.media_format_id = audio.media_format_id";

			if ($res =  $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getAudioById'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function getGroupsByAudioId($audioId) {
	
		$sql = "select * from xref_audio_group where audio_id = ".(int)$audioId;
		
		if ($res =  $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getGroupsByAudioId'),$this->_dbconn->error);
				return false;
			}
	}

	public function updateJob($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['updateJob']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			$sql = "update job set";
			
			$buffer = array();
			if (isset($valueArr['status']))
				array_push($buffer, " status = ".(int)$valueArr['status']);

			if (isset($valueArr['error_code']))
				array_push($buffer, " error_code = ".(int)$valueArr['error_code']);
			
			if (isset($valueArr['output_extension']))
				array_push($buffer, " output_extension = '".$valueArr['output_extension']."'");
				
			if (isset($valueArr['render_id']))
				array_push($buffer, " render_id = '".$valueArr['render_id']."'");
		
			$sql .= implode(",", $buffer);
			$sql .= ",update_ts = FROM_UNIXTIME(".$valueArr['updateTime'].")";
			$sql .= " where job.job_id = ".(int)$valueArr['jobID'];

			//echo "<SQL>".$sql."</SQL>";
			if ($res =  $this->_dbconn->query($sql)) {
				return true;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateJob'),$this->_dbconn->error);
				return false;
			}
		}
	}	
	
	public function getOpenMotifList() {
		$sql = 	" select motif.motif_id"
				.", motif.account_id"
				.", media_format.extension"
				.", media_format.extension"
				." from motif, media_format"
				." where motif.frames_count < 0"
				." and media_format.media_format_id = motif.media_format_id";
		
		if ($res =  $this->_dbconn->query($sql)) {
			return $res;
		}
		else {
			$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getOpenMotifList'),$this->_dbconn->error);
			return false;
		}
	}	
	
	public function sendHeartbeat($valueArr) {
		$success = false;

		$success = format::check_params($valueArr, $this->_required['sendHeartbeat']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
            $valueArr = format::$_returnArr;
            $sql =  "UPDATE rendermanager SET"
                .   " heartbeat_ts = '".$valueArr['CurrentTime']."'"
                .   ",message = '".$valueArr['Message']."'"
                .   ",status = 'normal'"
				.	",is_active = ".$valueArr['IsActive']
                .   " WHERE machinename ='".$valueArr['MachineName']."'"
				.	" AND license_key = '".$valueArr['LicenseKey']."'";

            $this->_dbconn->query($sql);
			$num_affected_rows = $this->_dbconn->affected_rows;
			
            $sql =  "UPDATE rendermanager SET"
                .   " status = 'offline' WHERE TIME_TO_SEC(TIMEDIFF('".$valueArr['CurrentTime']."', heartbeat_ts)) > 60";

            $this->_dbconn->query($sql);

            $sql = "SELECT * FROM rendermanager WHERE status ='normal' ORDER BY priority";
            if ($res =  $this->_dbconn->query($sql)) {
				return array($num_affected_rows, $res);
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->sendHeartbeat'),$this->_dbconn->error);
				return false;
			}
        }
    }

    public function getOpenJobs($is_preview) 
    {
        if ($is_preview == false)
        {
            $sql = "SELECT  job.*"
                    .",film.film_id"
                    .",film.name"
                    .",film.account_id"
                    .",film.audio_id"
                    .",film.url_hash"
                    .",film.custom3"
                    .",film.formats"
                    .",production.update_ts as production_update_ts"
                    .",production.status as production_status"
                    .",production.error_code as production_error_code"
					.",production.rendermachine_id"
					.",production.special_intro_music"
					.",production.email"
                    .",account_group.indicative"
                    .",account_group.abdicative"
                    .",account.login_username AS username"
                    .",xref_production_product_content.position as content_position"
                    .",xref_production_product_content.content_text"
                    .",xref_production_product_content.real_content_type as content_type"
                    .",xref_production_product_content.real_content_id"
                    .",media_format.extension"
                    .",product.product_id"
                    .",product_content.loader_name"
                    .",product.frame_count"
                    .",product.is_dicative"
                    .",product.previewFrame"
                    .",product.master_product_id"
                    .",product.in_frame"
                    .",product.out_frame"
                    .",product.name as product_name"
                    .",product.can_reformat"
                    .",job.position as job_position"
                    .",job.update_ts as job_update_ts"
                    .",job.create_ts as job_create_ts"
                    .",job.render_id"
                    ." FROM account, account_group, job, xref_production_product_content, film, media_format, motif, product_content, product, production"
                    ." WHERE production.status < 8"
                    ." AND job.production_production_id = production.production_id"
                    ." AND film.film_id = production.film_film_id"
                    ." AND account.account_id = film.account_id"
                    ." AND account_group.account_group_id = account.account_group_id"
                    ." AND production.delete_ts IS NULL"
                    ." AND xref_production_product_content.job_job_id = job.job_id"
                    ." AND media_format.media_format_id = motif.media_format_id"
                    ." AND motif_id = xref_production_product_content.real_content_id"
                    ." AND product_content.product_content_id = xref_production_product_content.product_content_product_content_id"
                    ." AND product.product_id = job.product_product_id"
                    ." order by job.production_production_id, job.position, xref_production_product_content.position";
        }
		else
        {
            $sql = "SELECT  job.*"
                .",film.film_id"
                .",film.name"
                .",film.account_id"
                .",film.audio_id"
                .",film.url_hash"
                .",film.custom3"
                .",film.formats"
                .",production.update_ts as production_update_ts"
                .",production.status as production_status"
                .",production.error_code as production_error_code"
                .",account_group.indicative"
                .",account_group.abdicative"
                .",account.login_username AS username"
                .",product.product_id"
                .",product.frame_count"
                .",product.is_dicative"
                .",product.previewFrame"
                .",product.master_product_id"
                .",product.in_frame"
                .",product.out_frame"
                .",product.name as product_name"
                .",product.can_reformat"
                .",job.position as job_position"
                .",job.update_ts as job_update_ts"
                .",job.create_ts as job_create_ts"
                .",job.render_id"
                ." FROM account, account_group, job, film, product, production"
                ." WHERE production.status < 8"
                ." AND production.is_preview = 1"
                ." AND job.production_production_id = production.production_id"
                ." AND film.film_id = production.film_film_id"
                ." AND account.account_id = film.account_id"
                ." AND account_group.account_group_id = account.account_group_id"
                ." AND production.delete_ts IS NULL"
                ." AND product.product_id = job.product_product_id";
        }
        if ($res =  $this->_dbconn->query($sql)) {
                return $res;
        }
        else {
            $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getOpenJobs'),$this->_dbconn->error);
            return false;
        }
    }

    public function getOpenDicativeJobs($valueArr) 
    {
        $sql = "SELECT *"
            ." ,production.production_id"
            ." ,job.job_id as job_id"
            ." ,job.position as job_position"
            . ",product.product_id"
            . ",product.in_frame"
            . ",product.out_frame"
            . ",product.name"
            . " FROM xref_production_product_content, job, product, production"
            . " WHERE production.status < 8"
            . " AND xref_production_product_content.job_job_id = job.job_id"
            . " AND job.production_production_id = production.production_id"
            . " AND product.product_id = job.product_product_id"
            . " AND real_content_type = 'empty'"
            . " AND production.delete_ts IS NULL"
            . " ORDER BY production.production_id, job.position";

        if ($res =  $this->_dbconn->query($sql)) {
            return $res;
        }
        else {
            $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getOpenDicativeJobs'),$this->_dbconn->error);
            return false;
        }
    }

	public function getOpenProductionList() {
        $date_str = date("Y-m-d H:i:s");
        $sql = "UPDATE settings SET value = '".$date_str."' WHERE name='manager_lastUpdate'";

        $this->_dbconn->query($sql);

        $sql = "UPDATE settings SET value = '1' WHERE name='manager_status'";

        $this->_dbconn->query($sql);

         $sql = "UPDATE settings SET value = '' WHERE name='manager_message'";

        $this->_dbconn->query($sql);


		$sql = "SELECT (SELECT COUNT(*)"
			. "FROM job, production"
			. " WHERE job.production_production_id = production.production_id) AS jobcount"
			. ",production.*"
			.",film.name"
			.",film.account_id"
			.",film.audio_id"
			.",film.url_hash"
			.",film.custom3"
			.",film.formats"
			.",production.update_ts as production_update_ts"
			.",account_group.indicative"
			.",account_group.abdicative"
			.",account.login_username AS username"
			." FROM production, film, account, account_group"
			." WHERE production.status < 8"
			." AND film.film_id = production.film_film_id"
			." AND account.account_id = film.account_id"
			." AND account_group.account_group_id = account.account_group_id"
			." AND production.delete_ts IS NULL";
			
		if ($res =  $this->_dbconn->query($sql)) {
			return $res;
		}
		else {
			$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getOpenProductionList'),$this->_dbconn->error);
			return false;
		}
	}
	
	public function getFinishedProductionList() {
		$sql = "SELECT (SELECT COUNT(*)"
			. "FROM job, production"
			. " WHERE job.production_production_id = production.production_id) AS jobcount"
			. ",production.*"
			.",film.name"
			.",film.account_id"
			.",film.audio_id"
			.",film.custom3"
            .",film.formats"
			.",film.url_hash"
			.",production.update_ts as production_update_ts"
			.",account_group.indicative"
			.",account_group.abdicative"
			.",account.login_username AS username"
			." FROM production, film, account, account_group"
			." WHERE production.status >= 7 AND error_code = 0"
			." AND film.film_id = production.film_film_id"
			." AND account.account_id = film.account_id"
			." AND account_group.account_group_id = account.account_group_id";
			
		if ($res =  $this->_dbconn->query($sql)) {
			return $res;
		}
		else {
			$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getFinishedProductionList'),$this->_dbconn->error);
			return false;
		}
	}	
	
	public function getJobsByProductionID($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['getJobsByProductionID']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;

            $is_preview = ($valueArr['is_preview'] == "1");

            if ($is_preview == false)
            {
			     $sql = "select *"
				. ",xref_production_product_content.position as content_position"
				. ",xref_production_product_content.content_text"
				. ",xref_production_product_content.real_content_type as content_type"
				. ",media_format.extension"
				. ",product_content.loader_name"
				. ",product.frame_count"
				. ",product.is_dicative"
				. ",product.previewFrame"
                . ",product.master_product_id"
                . ",product.in_frame"
                . ",product.out_frame"
				." ,job.position as job_position"
				." ,job.update_ts as job_update_ts"
				." ,job.create_ts as job_create_ts"
				." ,job.render_id"
				. " from job, xref_production_product_content, media_format, motif, product_content, product"
				. " where job.production_production_id = ".(int)$valueArr['productionID']
				. " and xref_production_product_content.job_job_id = job.job_id"
				. " and media_format.media_format_id = motif.media_format_id"
				. " and motif_id = xref_production_product_content.real_content_id"
				. " and product_content.product_content_id = xref_production_product_content.product_content_product_content_id"
				. " and product.product_id = job.product_product_id"
				. " order by job.position, xref_production_product_content.position";
			}
            else
            {
                $sql = "select *"
                . ",product.can_reformat"
                . ",product.frame_count"
                . ",product.is_dicative"
                . ",product.previewFrame"
                ." ,job.position as job_position"
                ." ,job.update_ts as job_update_ts"
                ." ,job.create_ts as job_create_ts"
                ." ,job.render_id"
                . " from job, product"
                . " where job.production_production_id = ".(int)$valueArr['productionID']
                . " and product.product_id = job.product_product_id"
                . " order by job.position";
            }
			if ($res =  $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getJobsByProductionID'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function getDicativeJobsByProductionID($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['getJobsByProductionID']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			$sql = "select *"
				." ,job.position as job_position"
				. ",product.product_id"
				. ",product.frame_count"
				. " from xref_production_product_content, job, product"
				. " where job_production_production_id = ".(int)$valueArr['productionID']
				. " and xref_production_product_content.job_job_id = job.job_id"
				. " and product.product_id = job.product_product_id"
				. " and real_content_type = 'empty'"
				. " group by product_content_id"
				. " order by job.position";
			
			if ($res =  $this->_dbconn->query($sql)) {
				return $res;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getDicativeJobsByProductionID'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function deleteFilm($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['deleteFilm']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			if ((int)$valueArr['permanent'] == 0)
			{
				$sql = "update film set delete_ts = now() where film_id = ".(int)$valueArr['filmID'];
			
				if (!$res =  $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->deleteFilm'),$this->_dbconn->error);
					return false;
				}
				else
					return (int)$valueArr['filmID'];
			}
			else if ((int)$valueArr['permanent'] == 1)
			{
				$sql = "delete from film where film_id = ".(int)$valueArr['filmID'];
				echo "<SQL>".$sql."</SQL>";
				
				if ($res =  $this->_dbconn->query($sql)) {
					$target = UPLOAD_DIR . '/'.(int)$valueArr['accountID'].'/productions/'.(int)$valueArr['filmID'];
					rmdir_recursive($target);
					return (int)$valueArr['filmID'];
				}
				else {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->deleteFilm'),$this->_dbconn->error);
					return false;
				}

			}
		}
	}
	
	public function deleteByAdmin($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['deleteByAdmin']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$timestamp = time();
			
			$sql = "update ".$valueArr['targetItem']." SET delete_ts = now() WHERE ".$valueArr['targetItem']."_id = ".$valueArr['itemID'];
			//echo "<SQL>".$sql."</SQL>";
			if ($res =  $this->_dbconn->query($sql)) {
				return $timestamp;
			}
			else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'motif->deleteByAdmin'),$this->_dbconn->error);
				return false;
			}
		}			
	}

    protected function getCodecsNameById($ids)
    {
        $idsArray = explode(',', $ids);
        $ids = implode(',', array_map(function($a) { return (int)$a; }, $idsArray));
        if (empty($ids)) {
            $ids = '0';
        }
        $sql = "SELECT GROUP_CONCAT(`name` SEPARATOR \"+\") AS data_str
                FROM codec_type WHERE codec_type_id IN ({$ids}) GROUP BY NULL";

        $res = $this->_dbconn->query($sql);
        $data = mysqli_fetch_array($res);

        return isset($data['data_str']) ? $data['data_str'] : '';
    }

    protected function createFilmSql($valueArr)
    {
        if (!isset($valueArr['formats']) || !is_array($valueArr['formats'])) {
            $valueArr['formats'] = [];
        }
        $formats = implode(',', array_map(function($item){
            return (int)$item;
        }, $valueArr['formats']));
        $codecs = $this->_dbconn->real_escape_string(format::getData($valueArr, 'custom3'));
        if (!empty($formats)) {
            $codecs = $this->getCodecsNameById($formats);
        }

        $sql = "INSERT INTO film"
            ." set"
            ."  account_id    = " . format::getData($valueArr, 'account_id', 0, 'int')
            .", production_id = " . format::getData($valueArr, 'production_id', null, 'string')
            .", name          = '" . $this->_dbconn->real_escape_string(format::getData($valueArr, 'name', '')) . "'"
            .", creation_time = FROM_UNIXTIME(" . format::getData($valueArr, 'creation_time', time(), 'int') .")"
            .", create_ts	  = FROM_UNIXTIME(" . format::getData($valueArr, 'create_ts', time(), 'int') .")"
            .", update_ts     = FROM_UNIXTIME(" . format::getData($valueArr, 'update_ts', time(), 'int') .")"
            .", audio_id      = " . format::getData($valueArr, 'audio_id', 0, 'int')
            .", duration      = " . format::getData($valueArr, 'duration', 0, 'int')
            .", size          = " . format::getData($valueArr, 'size')
            .", custom1       = '" . $this->_dbconn->real_escape_string(format::getData($valueArr, 'custom1', '')) . "'"
            .", custom2       = '" . $this->_dbconn->real_escape_string(format::getData($valueArr, 'custom2', '')) . "'"
            .", custom3       = '" . $codecs . "'"
            .", formats       = '" . $formats . "'"
            .", template      = '" . $this->_dbconn->real_escape_string(format::getData($valueArr, 'template', null, 'int')) . "'"
            .", url_hash      = '" . $this->_dbconn->real_escape_string(format::getData($valueArr, 'url_hash', '')) . "'"
            .";";

        return $sql;
    }
	
	public function migrateFilm($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['migrateFilm']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			$base_path = "/share/MD0_DATA/Web/virtualcampaign";

            $sql = $this->createFilmSql([
                "account_id"    => $valueArr['accountID'],
                "name"          => $valueArr['name'],
                "duration"      => $valueArr['frames'],
                "size"          => $valueArr['size'],
                "creation_time" => $valueArr['creationTime'],
                "create_ts"     => $valueArr['creationTime'],
                "audio_id"      => 1,
                "custom1"       => $valueArr['oldID'],
                "custom2"       => $valueArr['products'],
                "custom3"       => $valueArr['motifs'],
                "template"      => $valueArr['template'],
                "formats"       => $valueArr['formats'],
            ]);
			
			echo "<SQL>".$sql."</SQL>";
			
			if ($res =  $this->_dbconn->query($sql)) {
				$this->_film_id = $this->_dbconn->insert_id;
				
				if (strlen($valueArr['codecs']) > 0)
				{
					//copy old film
					$source = "";
					$target = "";
					
					if (!is_dir($base_path."/migration/accounts/".(int)$valueArr['accountID']."/productions"))
					{
						mkdir($base_path."/migration/accounts/".(int)$valueArr['accountID']."/productions");
					}
						
					$target_base = $base_path."/migration/accounts/".(int)$valueArr['accountID']."/productions/".$this->_film_id;
					
					if (!is_dir($target_base))
						mkdir($target_base);
					
					$codecFormats = explode("+", $valueArr['codecs']);
					
					$codecCount = count($codecFormats);
					
					for ($counter = 0; $counter < $codecCount; $counter++)
					{
						$fileFormats = explode(":", $codecFormats[$counter]);
						$fileExtension = strtolower($fileFormats[0]);
						$codecString = $fileFormats[1];
						
						$source = $base_path."/users/".(int)$valueArr['oldAccountID']."/jobs/".(int)$valueArr['jobID']."/".$valueArr['name']."_".$codecString.".".$fileExtension;
						$target = $target_base."/film_".$this->_film_id."_".$codecString.".".$fileExtension;
						
						echo "<SOURCE>".$source."</SOURCE>";
						echo "<TARGET>".$target."</TARGET>";
						
						copy($source, $target);	
						
						$source = $base_path."/users/".(int)$valueArr['oldAccountID']."/jobs/".(int)$valueArr['jobID']."/preview_m.flv";
						$target = $target_base."/preview_".$this->_film_id.".flv";
						copy($source, $target);	
					}
				}
				
				return $this->_film_id;
			} else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->migateFilm'),$this->_dbconn->error);
				return false;
			}
		}
	}
	
	public function createSingleProduction($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['createSingleProduction']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;	
			
			$totalFrameCount = $valueArr['durationTime'];
			$frameCounts = explode(".", $totalFrameCount);
			$frameSum = (int)$frameCounts[0]+(int)$frameCounts[1]+(int)$frameCounts[2];
			$hash_for_url = sha1($valueArr['user'].date("F j, Y, g:i a"));

            $time = time();
            //insert film data

            $is_preview = (isset($valueArr['is_preview']) && ((int)$valueArr['is_preview']) == 1);

            if ($is_preview == false)
            {
                if (isset($valueArr['credit_cost']) == false)
                    $credit_cost = 0;
                else
                    $credit_cost = $valueArr['credit_cost'];

                $sql = $this->createFilmSql([
                    "account_id"    => $valueArr['user'],
                    "production_id" => NULL,
                    "name"          => $valueArr['jobName'],
                    "duration"      => $frameSum,
                    "creation_time" => $time,
                    "create_ts"     => $time,
                    "update_ts"     => $time,
                    "audio_id"      => $valueArr['audioParam'],
                    "custom1"       => $valueArr['products'],
                    "custom2"       => $valueArr['motifIDs'],
                    "custom3"       => $valueArr['codecFormats'],
                    "template"      => $valueArr['template'],
                    "url_hash"      => $hash_for_url,
                    "formats"       => $valueArr['formats'],
                    "credit_cost"   => $credit_cost 

                ]);
    			//echo "<SQL>".$sql."</SQL>";
			

    			if ($res =  $this->_dbconn->query($sql)) {
    				$this->_film_id = $this->_dbconn->insert_id;
    			} else {
    				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createSingleProduction insert film'),$this->_dbconn->error);
    				return false;
    			}
            }
            else
            {
                $this->_film_id = 0;
            }

			if ((int)$valueArr['template'] == 1)
				return $this->_film_id;
				
			//insert production data
			$sql = "insert into production"
				. " set"
				. " status 			= 0"
				. ",error_code		= 0"
				. ",priority = -1"
				. ",creation_time 	= FROM_UNIXTIME(" .time().")"
				. ",create_ts 		= FROM_UNIXTIME(" .time().")"
				. ",film_film_id	= ".(int)$this->_film_id 
				. ",total_framecount= '".$this->_dbconn->real_escape_string($valueArr['durationTime'])."'"
				. ",special_intro_music = ".(int)$valueArr['specialIntroMusic']
				. ",email = '".$this->_dbconn->real_escape_string($valueArr['email'])."'"
                . ",is_preview = ".(int)$valueArr['is_preview']
				. ";";
			echo "<SQL>".$sql."</SQL>";
			
			if ($res =  $this->_dbconn->query($sql)) {
				$this->_production_id = $this->_dbconn->insert_id;
			} else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createSingleProduction insert production'),$this->_dbconn->error);
				return false;
			}
			
			if (strlen($valueArr['email']) > 0)
			{
				$sql = "update account set email = '".$this->_dbconn->real_escape_string($valueArr['email'])."' where account_id = ".(int)$valueArr['user'];
				$this->_dbconn->query($sql);
			}
			
			//insert production id into film

            if ($is_preview == false)			
            {
    			$sql = "update film set production_id = ".$this->_production_id." where film_id = ".$this->_film_id;
    			if (!$res =  $this->_dbconn->query($sql)) {
    				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createSingleProduction update film '),$this->_dbconn->error);
    				return false;
    			}
			}
			//insert job data
			
			$motifList = explode(".", $valueArr['motifIDs']);
			$motifFrameList = explode(".", $valueArr['motifFrames']);
			$productList = explode(",", $valueArr['products']);
			$productCount = count($productList);
			echo "<PC>".$productCount."</PC>";
			
			for ($i = 0; $i < $productCount; $i++) {			
				//insert job
				$sql = "insert into job"
					. " set"
					. " creation_time = FROM_UNIXTIME(".time().")"
					. ",create_ts = FROM_UNIXTIME(".time().")"
					. ",status = 0"
					. ",priority = -1"
					. ",error_code = 0"
					. ",position = ".((int)$i+1)
					. ",product_product_id = ".(int)$productList[$i]
					. ",production_production_id = ".(int)$this->_production_id
					. ",account_account_id = ".(int)$valueArr['user']
					. ";";
				echo "<SQL>".$sql."</SQL>";
				
				if ($res =  $this->_dbconn->query($sql)) {
					$this->_job_id = $this->_dbconn->insert_id;
				} else {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createSingleProduction insert job'),$this->_dbconn->error);
					echo "<ERR>".$sql."</ERR>";
					return false;
				}
				
                if ($is_preview == false)
                {
    				//insert product contents
    				$motifs = explode(",",$motifList[$i]);
    				$motifFrames = explode(",",$motifFrameList[$i]);
    				$motifCount = count($motifs);
    				echo "<MC>".$motifCount."</MC>";

    				for ($j = 0; $j < $motifCount; $j++) {
    					//get correct product_content_id from product and position

    					if (((int)$motifs[$j]) >= 0)
    					{
    						$sql = "select product_content_id from product_content where product_id = ".$productList[$i]." and position = ".($j+1);
    						echo "<SQL>".$sql."</SQL>";
    						$res_pcid = $this->_dbconn->query($sql);
    						$pcid = mysqli_fetch_array($res_pcid);
    						$product_content_id = $pcid['product_content_id'];

    						if (is_numeric($motifs[$j]))
    						{
    							$content_id = (int)$motifs[$j];
    							if (((int)$motifFrames[$j]) > 1)
    								$content_type = "film";
    							else
    								$content_type = "motif";
    							$custom = "";
    						}
    						else
    						{
    							$content_id = -1;
    							$content_type = "text";
    							$custom = $this->_dbconn->real_escape_string($motifs[$j]);
    						}
    					}
    					else
    					{
    						$content_id = -1;
    						$content_type = "empty";
    						$custom = "";
    						$product_content_id = 0;
    					}
    					$sql = "insert into xref_production_product_content"
    						. " set"
    						. " real_content_id						= ".$content_id
    						. ",real_content_type					= '".$content_type."'"
    						. ",create_ts		 					= FROM_UNIXTIME(".time().")"
    						. ",job_job_id							= ".(int)$this->_job_id
    						. ",job_production_production_id		= ".(int)$this->_production_id
    						. ",product_content_product_content_id	= ".(int)$product_content_id
    						. ",is_looping = 0"
    						. ",position 							= ".((int)$j+1)
    						. ";";

    					echo "<SQL>".$sql."</SQL>";
    					if ($res =  $this->_dbconn->query($sql)) {
    						$this->xref_id = $this->_dbconn->insert_id;
    					} else {
    						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createSingleProduction insert product content'),$this->_dbconn->error);
    						return false;
    					}
    				}
                }
			}
			return $this->_film_id;
		}
	}
	
	public function getFilmsByAccount($valueArr) {
		$success = false;
		
		$success = format::check_params($valueArr, $this->_required['getFilmsByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			$sql = "select "
					." film.film_id"
					.",film.account_id"
					.",film.production_id"
					.",film.name"
					.",film.description"
					.",film.creation_time"
					.",film.duration"
					.",film.custom1"
					.",film.custom2"
					.",film.custom3"
                    .",film.formats"
					.",film.transaction_id"
					.",film.size"
					.",film.url_hash"
					.",IFNULL(_p.status, 0) AS job_status"
					." FROM film"
                    ." LEFT JOIN production AS _p ON _p.production_id = film.production_id"
					." where film.creation_time >= UNIX_TIMESTAMP(film.creation_time) >= ".$valueArr['min_date']
					." and film.delete_ts IS NULL"

                ." and ((film.template = 1 and film.size IS NULL) OR (film.template = 0 AND (film.size IS NOT NULL OR film.creation_time > '" . (date('Y-m-d H:i:s', time() - (24 * 60 * 60))) . "'  )))";
//                ." and ((film.template = 1 and film.size IS NULL) OR (film.template = 0 and film.size IS NOT NULL))";
					
			if (isset($valueArr['accountID']))
				$sql .= " and film.account_id = ".$valueArr['accountID'];
					
			echo "<SQL>".$sql."</SQL>";
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getMotifListByAccount'),$this->_dbconn->error);
				return null;
			}
			
			return $res;
		}
	}
	
	public function getFilmProducts($filmID) {
		$sql = "select "
				." product.product_id"
				.",product.name"
				." from product"
				." where product_id = job.product_product_id and job.production_id = production.production_id and production.film_film_id = ".$filmID;
				
		if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getMotifListByAccount'),$this->_dbconn->error);
				return null;
			}
			
			return $res;
	}
	
	/**
	 * createFilmProduction
	 * 
	  * @param array $valueArray
	 *
	 * @return result-set or null
	 */
	public function createFilmProduction($valueArr)  {
		$success = false;
		//validate film-Array

		$filmArr = null;		
		if(isset($valueArr['films'])) {
			$filmArr = json_decode(urldecode($valueArr['films']), true);
		}
/*		
echo '<br/>decoded<br/>';		
var_dump($filmArr);
*/		
		if(!is_array($filmArr)) {
			$this->_error .= 'Wrong format for film-Array.';
			return null;
		}
		//validate rest
		$success = format::check_params($valueArr, $this->_required['createFilmProduction']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
	
			//create film production
/* old script: saveFilm.php (oder?)
 * "INSERT INTO films (name, duration, products, motifIDs, ownerID, creationDate) 
 * VALUES ('".$_POST['Name']."', -1, '".$_POST['productIDs']."', '".$_POST['motifIDs']."', ".$_POST['userID'].", ".time().")";
 */			
			$init_all = false;
			
			//insert production
			$sql = "insert into production"
					." set" 
					.	" account_id		= " . (int)$valueArr['accountID']
					.	",creation_time 	= now()"
					//TODO: is_preview?  bisher laut CreateStmt default 0
					//TODO: status initial? bisher laut CreateStmt default 0						 
					//TODO: priority? bisher laut CreateStmt default 0
					.	",create_ts			= now()"
					.";";
			if ($res =  $this->_dbconn->query($sql)) {
				$this->_production_id = $this->_dbconn->insert_id;
			} else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createFilmProduction insert production'),$this->_dbconn->error);
				return false;
			}
			
			//insert job
			$sql = "insert into job"
					." set" 
					.	" production_id		= " . (int)$this->_production_id
					//TODO: status initial? bisher laut CreateStmt default 0
					.	",creation_time 	= now()"
					.	",create_ts			= now()"
					.";";
			if ($res =  $this->_dbconn->query($sql)) {
				$this->_job_id = $this->_dbconn->insert_id;
			} else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createFilmProduction insert job'),$this->_dbconn->error);
				$init_all = true;
			}
			
			//insert films
			foreach ($filmArr as $key => $value) {
/*				
echo '<br/><br/>';				
var_dump($value);
echo '<br/><br/>';
*/				
				//insert film
				if(!$init_all) {
					$sql = "insert into film"
							." set" 
							.	" account_id		= " . (int)$valueArr['accountID'] 
							.	",job_id			= " . (int)$this->_job_id
							.	",audio_id		 	= " . (int)$value['audioID']
							.	",name				= '" . $this->_dbconn->real_escape_string($value['name']) . "'"
							.	",description		= '" . $this->_dbconn->real_escape_string($value['description']) . "'"
						  	.	",creation_time 	= now()"
						  	.	",flv_url	 		= ''"	//TODO: noch prüfen... wird nach 'generierung' erst gesetzt, oder?
						  	.	",thumbnail_url		= ''"	//TODO: noch prüfen... wird nach 'generierung' erst gesetzt, oder?
						  	.	",duration		 	= -1"    
						  	.	",preview		 	= " . (int)$value['preview'] 
							.	",create_ts			= null"
							.";";
//echo '<br/>', $sql;					
					if ($res =  $this->_dbconn->query($sql)) {
						$this->_film_id = $this->_dbconn->insert_id;
					} else  {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createFilmProduction insert film'),$this->_dbconn->error);
						$init_all = true;
					}
				}
//TODO: fehlt noch xref_film_codec_type				
				//insert xref_film_codec_type
				if(!$init_all) {
				}
				//insert xref_film_product ($_POST['productIDs'])
				if(!$init_all) {
					$products = explode(',', $value['productIDs']);
					if(!is_array($products)) {
						$products = array($products); 
					}
					foreach($products as $keyp => $valuep) {
						$sql = "insert into xref_film_product"
							." set" 
							.	" film_id			= " . (int)$this->_film_id 
							.	",product_id		= " . (int)$valuep
							.	",create_ts			= null"
							.";";
//echo '<br/>', $sql; 							
						if ($res =  $this->_dbconn->query($sql)) {
							//update real_usage from accountgroup
							$sql = "update xref_account_group_product set real_usage = real_usage + 1"
								." where"
								.	" account_group_id = (select account_group_id from account where account_id = " . (int)$valueArr['accountID'] . ")"
								.	" and product_id		= " . (int)$valuep 
								.";";
							$this->_dbconn->query($sql);
						} else {
							$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createFilmProduction insert xref_film_product'),$this->_dbconn->error);
							$init_all = true;
						}
					}
				}
				//insert xref_film_product_content ($_POST['motifIDs'])
				if(!$init_all) {
					$motifs = explode('.', $value['motifIDs']);
					if(!is_array($motifs)) {
						$motifs = array($motifs); 
					}
					foreach($motifs as $keym => $valuem) {
						$sql = "insert into xref_film_product_content"
							." set" 
							.	" film_id				= " . (int)$this->_film_id 
							.	",product_content_id	= 1"		//TODO. wie kommt das per neu?!?
							.	",frames_count			= ''"		//TODO. wie kommt das per neu?!?
							.	",real_content_id		= " . (int)$valuem
							.	",real_content_type		= 'motif'"
							.	",create_ts			= null"
							.";";
//echo '<br/>', $sql; 								
						if (!$res =  $this->_dbconn->query($sql)) {
							$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createFilmProduction insert xref_film_product_content motif'),$this->_dbconn->error);
							$init_all = true;
						}
					}
				}
				
				
				
				
				if($init_all) {
					//Reihenfolge wg FKs beachten!
					
					//remove xref_film_product_content
					$sql = "delete from xref_film_product_content where film_id = " . (int)$this->_film_id;
					$this->_dbconn->query($sql);
					
					//remove xref_film_product
					$sql = "delete from xref_film_product where film_id = " . (int)$this->_film_id;
					$this->_dbconn->query($sql);
					
					//remove xref_film_codec_type
//TODO: gibt es zwar noch nicht, aber hier schon mal mit löschen
					$sql = "delete from xref_film_codec_type where film_id = " . (int)$this->_film_id;
					$this->_dbconn->query($sql);
					
					
					//remove film
					$sql = "delete from film where job_id = " . (int)$this->_job_id;
					$this->_dbconn->query($sql);
					
					//remove job
					$sql = "delete from job where job_id = " . (int)$this->_job_id;
					$this->_dbconn->query($sql);
					
					//remove production
					$sql = "delete from production where production_id = " . (int)$this->_production_id;
					$this->_dbconn->query($sql);
					
					return false;
				} else {
					return true;
				}
			}//foreach
		}//validation
	}
	
	public function setProductOwnership($valueArr) {
		$success = false;

		//validate
		$success = format::check_params($valueArr, $this->_required['setProductOwnership']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;		
			
			$owner_list = explode(",",$valueArr['ownerIDList']);
			$product_id = (int)$valueArr['productID'];
			
			$owner_count = count($owner_list);
			
			for ($i=0; $i<$owner_count; $i++)
			{
				$sql = "insert into xref_account_group_product"
						." set"
						." account_group_id = ".(int)$owner_list[$i]
						.",max_usage		= -1"
						.",product_id 		= ".(int)$product_id
						.";";
				
				if (!$res =  $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->setProductOwnership'),$this->_dbconn->error);
					return false;
				}
			}
		}
	}
	
	public function createProduct($valueArr)  {
		$success = false;

		//validate
		$success = format::check_params($valueArr, $this->_required['createProduct']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;

			$codec_id_list = explode(",", $valueArr['productFormatIDList']);
			$product_content_format_id_list = explode(",", $valueArr['productContentIDList']);
			$product_content_can_loop_list = explode(",", $valueArr['productContentCanLoopList']);
			$product_content_accepts_film_list = explode(",", $valueArr['productContentAcceptsFilmList']);

            if (isset($valueArr['credit_cost']) == false)
                $credit_cost = 0;
            else
                $credit_cost = $valueArr['credit_cost']; 

			//insert product
			$sql = "insert into product"
					." set";

			if (isset($valueArr['id']))
				$sql .=" product_id			= " .(int)$valueArr['id']
					.	",product_group_id	= " . (int)$valueArr['productGroupID']
					.	",description		='" . $this->_dbconn->real_escape_string($valueArr['description']) . "'"
					.	",name 				='" . $this->_dbconn->real_escape_string($valueArr['name']) . "'"
					.	",location 			='" . $this->_dbconn->real_escape_string($valueArr['location']) . "'"
					.	",previewFrame		= " . (int)$valueArr['previewFrame']
					.	",is_dicative		= " . (int)$valueArr['is_dicative']
					.	",credit_cost	= " . $credit_cost
					.";";			
					
			if ($res =  $this->_dbconn->query($sql)) {
				$this->_product_id = $this->_dbconn->insert_id;
			} else {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createProduct insert product'),$this->_dbconn->error);
				return false;
			}
			
			//insert all allowed formats for this product
			$N = count($codec_id_list);
			for ($i=0; $i<$N; $i++)
			{
				$sql = "insert into product_has_codec_type"
						." set"
						.	" product_product_id		=	" .(int)$valueArr['id']
						.	",codec_type_codec_type_id	=	" .(int)$codec_id_list[$i]
						.";";
						
				if (!$res = $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createProduct link product - codec'),$this->_dbconn->error);
					return false;
				}
			}
			
			//insert foreign keys
			$N = count($product_content_format_id_list);
			for ($i=0; $i<$N; $i++)
			{
				$sql = "insert into product_content"
						." set"
						.	" content_format_id		=	" .(int)$product_content_format_id_list[$i]
						.	",product_id			=	" .(int)$valueArr['id']
						.	",can_loop				=	" .(int)$product_content_can_loop_list[$i]
						.	",accepts_film			=	" .(int)$product_content_accepts_film_list[$i]
						.	",position				=	" .(int)($i+1)
						.";";
			
				echo "<SQL>".$sql."</SQL>";
				if ($res = $this->_dbconn->query($sql)) {
					$this->_product_content_id = $this->_dbconn->insert_id;
				} else {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->createProduct create product content'),$this->_dbconn->error);
					return false;
				}		
			}
		} //validate
		if (isset($this->_product_id))
			return $this->_product_id;
		else
			return null;
	}
	
	
	/**
	 * getProductionListByAccount
	 * 
	  * @param array $valueArray
	 *
	 * @return result-set or null
	 */
	public function getProductionListByAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getProductionListByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			//get production list
/* old script: new_getfilms.php (oder?)
 * "SELECT * FROM films WHERE ownerID=".$user." ORDER BY creationDate DESC";
 */			
			//auslesen aus production / job / film / xref_film_codec_type / codec_type / xref_film_product / xref_film_product_content 
			$sql="select"
				." f.film_id"
				.",f.account_id"
				.",f.job_id"
				.",f.audio_id"
				.",f.name"
				.",f.creation_time"
				.",f.flv_url"
				.",f.duration"
				.",f.preview"
				.",p.status as production_status"		//TODO: oder hier job.status???
				
				.",(select group_concat(ct.name separator '+') from codec_type ct"
				.	" inner join xref_film_codec_type xfct"
				.	" on xfct.codec_type_id = ct.codec_type_id"
				.	" where xfct.film_id 	= f.film_id"
				.") as codec_types_string"				//e.g. WMV:DVD+WMV:WEB (concat codec_type.name)
				
				.",(select group_concat(xfpc.real_content_id separator '.') from xref_film_product_content xfpc"
				.	" where xfpc.film_id = f.film_id"
				. 	" and xfpc.real_content_type = 'motif'"
				.") as motif_ids_string"				//e.g. 1.2.3.4.5 (concat xref_film_product_content.real_content_id)
			
				.",(select group_concat(pr.product_group_id separator ',') from product pr"
				.	" where pr.product_id = xfp.product_id" //s. 'main-join'
				.") as product_group_ids_string"		//e.g. 1,2,3,4,5 (concat product.product_group_id)
				

				.",(select group_concat(xfct.size_kb separator '+') from xref_film_codec_type xfct"
				.	" where xfct.film_id = f.film_id"
				.") as sizes_string"				//e.g. 12345+12345+12345
				
			." from"
				." production p"
				
				." inner join job j"
				." on p.production_id = j.production_id"
				
				." inner join film f"
				." on j.job_id = f.job_id"
				
				." inner join xref_film_product xfp"	//TODO: prüfen, ob 1:1 zu Film, sonst so nicht möglich, dann auch hier in subselect
				." on f.film_id = xfp.film_id"
				
			." where" 
				." f.account_id = " . (int)$valueArr['accountID']
			." order by"
				." f.creation_time desc"
				.";";
//echo $sql;				
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getProductionListByAccount'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}
		
	public function getDicativesByAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getDicativesByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
						
			if (isset($valueArr['accountGroupID']))
			{
				$sql = "select DISTINCT(product.product_id)"
					 . ", product.*"
					." from product, account_group, xref_account_group_product"
					." where"
					." ((product.product_id = account_group.indicative or product.product_id = account_group.abdicative) AND account_group.account_group_id = ".(int)$valueArr['accountGroupID'].")"
					." OR (product.is_dicative=1"
					." and product.delete_ts IS NULL"
					." and xref_account_group_product.product_id = product.product_id and xref_account_group_product.account_group_id = ".(int)$valueArr['accountGroupID'].")"
					.";";
			}
			else
			{
				$sql = "select DISTINCT(product.product_id)"
					 . ", product.*"
                    ." from product"
                    //." from product, account_group, xref_account_group_product"
					." where product.is_dicative=1"
					." and product.delete_ts IS NULL"				
					//." and xref_account_group_product.product_id = product.product_id"
					.";";
			}
			echo "<SQL>".$sql."</SQL>";
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getDicativesByAccount'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}
	
	public function getContentTypeListByAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getContentTypeListByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$sql = "select" 
				." product_content.content_format_id"
				.",product_content.product_id"
				.",product_content.can_loop"
				.",product_content.accepts_film"
				.",product_content.position"
				.",product_content.loader_name"
				.",product_content.real_content_type"
				.",content_format.name"
				.",content_format.aspect"
				." from product_content"
				.",xref_account_group_product"
				.",content_format"
				." where (product_content.position > 0 and ";
				
				if (isset($valueArr['accountGroupID']))
				{
					$sql .= "product_content.product_id = xref_account_group_product.product_id and ";
				}
				$sql .= "product_content.delete_ts IS NULL"
 				." and content_format.content_format_id = product_content.content_format_id";
			
			if (isset($valueArr['accountGroupID']))
			{
				$sql .= " and xref_account_group_product.account_group_id = ".(int)$valueArr['accountGroupID'];
			}
			$sql .= ")"
				." group by product_content.product_content_id"
				." order by product_content.product_id, product_content.position";
				
			print("<SQL>".$sql."</SQL>");
				
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getContentTypeListByAccount'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}
	
	public function getShortProductListByAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getShortProductListByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
						
			$sql="select * from product";
					
					if (isset($valueArr['accountGroupID']))
					{
						$sql .= ",xref_account_group_product";																
						$sql .= " where ";
						$sql .= "(xref_account_group_product.product_id = product.product_id and xref_account_group_product.account_group_id = " . (int)$valueArr['accountGroupID'].")";
										
						if (isset($valueArr['indicative']))
						{
							$sql .= "or (product.product_id = ".$valueArr['indicative']." or product.product_id = ".$valueArr['abdicative'].")";
						}				
						$sql .= " and ";
					}
					else
						$sql .= " where";
					$sql .= " product.delete_ts is null order by product.product_id";
			echo "<SQL>".$sql."</SQL>";
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getShortProductListByAccount'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}	
	
	public function getProductListByAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getProductListByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
						
			$sql="select"
					." product.product_id"	
					.",product.name as product_name"
					.",product.description"
					.",product.location"
					.",product.product_group_id"
					.",product.credit_cost"
					.",product.update_ts"
					.",product.frame_count"
					.",product.is_dicative"
                    .",product.credit_cost"
					.",product_content.accepts_film"
					.",product_content.can_loop"
					.",product_content.position"
					.",product_content.real_content_type"
					.",product_content.loader_name"
					.",content_format.content_format_id"
					.",content_format.name as format_name"
					.",content_format.aspect"
					.",product_has_codec_type.codec_type_codec_type_id"
					." from product, product_content, content_format, product_has_codec_type";
					
					if (isset($valueArr['accountGroupID']))
						$sql .= ",xref_account_group_product";
					
					$sql .= " where (product_content.product_id = product.product_id"
					." and content_format.content_format_id = product_content.content_format_id"
					." and product_content.delete_ts IS NULL"
					." and product.delete_ts IS NULL"
					." and product_has_codec_type.product_product_id = product.product_id)";				
											
					if (isset($valueArr['accountGroupID']))
					{
						$sql .= " and ((xref_account_group_product.product_id = product.product_id and xref_account_group_product.account_group_id = " . (int)$valueArr['accountGroupID'].")";
										
						if (isset($valueArr['indicative']))
						{
							$sql .= "or (product.product_id = ".$valueArr['indicative']." or product.product_id = ".$valueArr['abdicative'].")";
						}				
						$sql .= ")";
					}
						
					$sql .= " order by product.product_id, product_content.position;";
			echo "<SQL>".$sql."</SQL>";
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getProductistByAccount'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}	
	
	public function checkProductionStatus($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['checkProductionStatus']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$filmIdList = explode(",",$valueArr['filmIDs']);
			
			$filmOrString = implode(" OR film_id=", $filmIdList);
			
			$sql = "select film_id from film where size IS NOT NULL and (film_id=".$filmOrString.")";
			echo "<SQL>".$sql."</SQL>";
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->checkProductionStatus'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}

    public function checkProductionStatusAdv($valueArr) {
        if(false === format::check_params($valueArr, $this->_required['checkProductionStatus'])) {
            $this->_error .= error::buildErrorString(format::$_errorArr);

            return null;
        } else {
            $valueArr = format::$_returnArr;
            $filmIdList = array_map(function($item) {
                return (int)$item;
            }, explode(",", $valueArr['filmIDs']));
            $filmIdList[] = 0;
            $filmListString = implode(',', $filmIdList);

            $sql = "SELECT
                      _f.film_id,
                      IFNULL(_p.status, 0) AS job_status
                    FROM film AS _f
                    LEFT JOIN production AS _p
                      ON _p.production_id = _f.production_id
                    WHERE _f.film_id IN ({$filmListString})";

            if (!$res =  $this->_dbconn->query($sql)) {
                $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->checkProductionStatus'),$this->_dbconn->error);
                return null;
            }
            return $res;
        }
    }


	/**
	 * loadFilmProductionByAccount
	 * 
	  * @param array $valueArray
	 *
	 * @return result-set or null
	 */
	public function loadFilmProductionByAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['loadFilmProductionByAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			//load film production
/* old script: ???
 * 
 */			
			//auslesen aus production / job / film / xref_film_codec_type / codec_type / xref_film_product / xref_film_product_content 
			$sql="select"
				." f.film_id"
				.",f.account_id"
				.",f.job_id"
				.",f.audio_id"
				.",f.name"
				.",f.creation_time"
				.",f.flv_url"
				.",f.duration"
				.",f.preview"
				.",p.status as production_status"		//TODO: oder hier job.status???
				
				.",(select group_concat(ct.name separator '+') from codec_type ct"
				.	" inner join xref_film_codec_type xfct"
				.	" on xfct.codec_type_id = ct.codec_type_id"
				.	" where xfct.film_id 	= f.film_id"
				.") as codec_types_string"				//e.g. WMV:DVD+WMV:WEB (concat codec_type.name)
				
				.",(select group_concat(xfpc.real_content_id separator '.') from xref_film_product_content xfpc"
				.	" where xfpc.film_id = f.film_id"
				. 	" and xfpc.real_content_type = 'motif'"
				.") as motif_ids_string"				//e.g. 1.2.3.4.5 (concat xref_film_product_content.real_content_id)
			
				.",(select group_concat(pr.product_group_id separator ',') from product pr"
				.	" where pr.product_id = xfp.product_id"
				.") as product_group_ids_string"		//e.g. 1,2,3,4,5 (concat product.product_group_id)
				

				.",(select group_concat(xfct.size_kb separator '+') from xref_film_codec_type xfct"
				.	" where xfct.film_id = f.film_id"
				.") as sizes_string"				//e.g. 12345+12345+12345
				
			." from"
				." production p"
				
				." inner join job j"
				." on p.production_id = j.production_id"
				
				." inner join film f"
				." on j.job_id = f.job_id"
				
				." inner join xref_film_product xfp"	//TODO: prüfen, ob 1:1 zu Film, sonst so nicht möglich
				." on f.film_id = xfp.film_id"
				
			." where" 
				." f.film_id 		= " . (int)$valueArr['filmID']
				." and f.delete_ts IS NULL"
				." and f.account_id = " . (int)$valueArr['accountID']
			." order by"
				." f.creation_time desc"
				.";";
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->loadFilmProduction'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}
	
	
	/**
	 * getProductList
	 * 
	 *
	 * @return result-set or null
	 */
	 
	public function getProductList() {
	
			//auslesen aus product, product_format, product_group 
			$sql="select"
				." p.product_id"
				.",p.product_group_id"
				.",p.product_format_id"
				.",p.name as product_name"
				.",p.description as product_descr"
				.",p.location as product_location"
				.",p.creation_time as product_creation_time"
				.",p.render_time as product_render_time"
                .",p.credit_cost"                
				
				.",pg.parent_group_id"
				.",pg.name as product_group_name"
				
				.",pf.name as product_format_name"
				.",pf.width as product_format_width"
				.",pf.height as product_format_height"
				
			." from"
				." product p"
				
				." inner join product_group pg"
				." on p.product_group_id = pg.product_group_id"
				
				." inner join product_format pf"
				." on p.product_format_id = pf.product_format_id"

			." order by"
				." product_name"
				.";";
//echo $sql;				
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getProductList'),$this->_dbconn->error);
				return null;
			}
			return $res;
	}

	public function getProductContentFormatList() {

			//auslesen aus content_format
			$sql="select"
				." c.content_format_id"
				.",c.name as content_format_name"
				.",c.aspect as content_format_aspect"
				.",c.width as content_format_width"
				.",c.height as content_format_height"
			." from"
				." content_format c"
			." where delete_ts IS NULL"
			." order by"
				." content_format_name"
				.";";
						
//echo $sql;
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getProductList'),$this->_dbconn->error);
				return null;
			}
			return $res;
	}
	
	public function checkProductID($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['checkProductID']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$sql = "select count(*) as total, max(product_id) as lowest from product where product_id = ".$valueArr['newProductID'];
			
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->checkProductID'),$this->_dbconn->error);
				return null;
			}
			else
			{
				return $res;
			}
		}
	}
	
	public function workOnProduct($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['workOnProduct']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
		
			if ($valueArr['task'] == "addProduct") {
                $this->_product_id = $this->saveProduct($valueArr);
            } else if ($valueArr['task'] == "updateProduct") {
				$this->_product_id = $this->updateProduct($valueArr);
                /*
                // Why it added for?
				$sql = "delete from xref_account_group_product"
						." where "
						." product_id 		= ".(int)$valueArr['productID']
						.";";
						
				if (!$res = $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->workOnProduct'),$this->_dbconn->error);
					return null;
				}
                */
			}
			
			return $this->workOnProductMotifAssociations($valueArr['commands'], $valueArr['productID']);
		}
	}

    protected function processProductAdditionalData($valueArr)
    {
        $subSql = '';
        if (isset($valueArr['productType'])) {
            $subSql .= ", product_type_id = " . (int)$valueArr['productType'];
        }
        if (isset($valueArr['codecType']) && is_array($valueArr['codecType'])) {
            $items = array_map(function($i) { return (int)$i; }, $valueArr['codecType']);
            $items = array_diff($items, ['', '0', 0]);
            $subSql .= ", allowed_codec_types = '" . implode(',', $items) . "'" ;
        }

        if (isset($valueArr['can_reformat'])) {
            $subSql .= ", can_reformat = " . (int)(bool)$valueArr['can_reformat'];
        }
        if (isset($valueArr['resolution'])) {
            $subSql .= ", resolution = " . (int)$valueArr['resolution'];
        }

        if (isset($valueArr['MasterProductId'])) {
            $subSql .= ", master_product_id = " . (int)$valueArr['MasterProductId'];
        }

        if (isset($valueArr['OutFrame'])) {
            $subSql .= ", out_frame = " . (int)$valueArr['OutFrame'];
        }

        if (isset($valueArr['InFrame'])) {
            $subSql .= ", in_frame = " . (int)$valueArr['InFrame'];
        }

        return $subSql;
    }

	public function saveProduct($valueArr)
    {
		$sql = "insert into product set"
			." product_id = ".(int)$valueArr['productID']
			.",product_group_id = ".(int)$valueArr['productCategoryID']
			.",product_format_id = 0"
			.",description = '".$valueArr['productDescription']."'"
			.",name = '".$valueArr['productName']."'"
			.",location = '".$valueArr['productLocation']."'"
			.",previewFrame = ".(int)$valueArr['productPreviewFrame']
			.",is_dicative = ".(int)$valueArr['is_dicative']
            . $this->processProductAdditionalData($valueArr)
			.",frame_count = ".(int)$valueArr['productFrameCount'];

			echo "<SQL>".$sql."</SQL>";
			if (!$res = $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->workOnContentFormat'),$this->_dbconn->error);
				return null;
			}

		return $res;
		
	}
	
	public function updateProduct($valueArr)
    {
		$sql = "update product set"
			." product_group_id = ".(int)$valueArr['productCategoryID']
			.",product_format_id = 0"
			.",description = '".$valueArr['productDescription']."'"
			.",name = '".$valueArr['productName']."'"
			.",location = '".$valueArr['productLocation']."'"
			.",previewFrame = ".(int)$valueArr['productPreviewFrame']
			.",is_dicative = ".(int)$valueArr['is_dicative']
			.",frame_count = ".(int)$valueArr['productFrameCount']
            . $this->processProductAdditionalData($valueArr)
			." WHERE product_id = ".(int)$valueArr['productID'];
			
			echo "<SQL>".$sql."</SQL>";

			if (!$res = $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->workOnContentFormat'),$this->_dbconn->error);
				return null;
			}
		
		return $res;	
	}	
	
	private function workOnProductMotifAssociations($commandString, $productID) {
	
		$commands = explode(",", $commandString);
		
		$command_count = count($commands);
		
		$sql = "update product_content set delete_ts = now() where product_id = ".$productID;
		echo "<SQL>".$sql."</SQL>";
		$this->_dbconn->query($sql);
				
		for ($i=0; $i<$command_count; $i++)
		{
			$command = explode(".", $commands[$i]);
			$type = $command[0];
			
			if ($type == "addGroup2Product")
			{
				$account_group_id = $command[1];
				$product_id = $command[2];		
				
				$sql = "insert into xref_account_group_product"
					." set"
					." account_group_id = ".(int)$account_group_id
					.",max_usage		= -1"
					.",product_id 		= ".(int)$product_id
					.";";
				
				echo "<SQL>".$sql."</SQL>";
			
				if (!$res = $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->workOnProductMotifAssociations'),$this->_dbconn->error);
					return null;
				}				
			}
			else if ($type == "addMotif2Product")
			{
				$motif_id = $command[1];
				$product_id = $command[2];
				$can_loop = $command[3];
				$accepts_film = $command[4];
				$position = $command[5];
				$loader_name = $command[6];
				$real_content_type = $command[7];

				$sql = "update product_content set"
						  ." content_format_id = "	.$motif_id
						  .",can_loop = "			.$can_loop
						  .",accepts_film = "		.$accepts_film
						  .",loader_name = '"		.$loader_name."'"
						  .",real_content_type = '"	.$real_content_type."'"
						  .",delete_ts = NULL"
						  ." where product_id = "	.$product_id				
						  ." and position = "		.$position;		
			
				$res = $this->_dbconn->query($sql);			
				$num_affected_rows = $this->_dbconn->affected_rows;
				echo "<SQL>".$sql."</SQL>";
				
				if ($num_affected_rows == 0)
				{
					echo"<SQL>0 Rows affected</SQL>";
					
					$sql = "insert into product_content set"
						  ." content_format_id = "	.$motif_id
						  .",product_id = "			.$product_id
						  .",can_loop = "			.$can_loop
						  .",accepts_film = "		.$accepts_film
						  .",position = "			.$position
						  .",loader_name = '"		.$loader_name."'"
						  .",real_content_type = '"	.$real_content_type."'";
						  
					echo "<SQL>".$sql."</SQL>";

					if (!$res = $this->_dbconn->query($sql)) {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->workOnProductMotifAssociations'),$this->_dbconn->error);
						return null;
					}
				}  				
			}
		}
	}
	
	public function workOnContentFormat($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['workOnContentFormat']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			$valueArr = format::$_returnArr;
			
			if ($valueArr['task'] == "createContentFormat")
			{
				$sql = "insert into content_format set"
					  ." name = '".$this->_dbconn->real_escape_string($valueArr['contentFormatName'])."'"
					  .",width = '".$this->_dbconn->real_escape_string($valueArr['width'])."'"
					  .",height = '".$this->_dbconn->real_escape_string($valueArr['height'])."'"
					  .",aspect = ".$this->_dbconn->real_escape_string($valueArr['contentFormatAspect']);
					  					  
			}
			else if ($valueArr['task'] == "updateContentFormat")
			{
				$sql = "update content_format set"
					  ." name = '".$this->_dbconn->real_escape_string($valueArr['contentFormatName'])."'"
					  .",aspect = ".$this->_dbconn->real_escape_string($valueArr['contentFormatAspect'])
                      .",width = '".$this->_dbconn->real_escape_string($valueArr['width'])."'"
                      .",height = '".$this->_dbconn->real_escape_string($valueArr['height'])."'"
					  ." where content_format_id = ".(int)$valueArr['contentFormatID'];
			}
		
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->workOnContentFormat'),$this->_dbconn->error);
				return null;
			}
			if ($valueArr['task'] == "createContentFormat")
			{
				echo "<ContentFormatID>".$this->_dbconn->insert_id."</ContentFormatID>";
			}
			else if ($valueArr['task'] == "editContentFormat")
			{
				echo "<ContentFormatID>".$valueArr['contentFormatID']."</ContentFormatID>";				
			}
			return $res;
		}
	}
	
	public function getAudioList($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getAudioList']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			$valueArr = format::$_returnArr;
			
			$sql = "select * from audio where delete_ts IS NULL";
			
			if (isset($valueArr['accountID']))
			{
				$sql .= " and account_id = ".(int)$valueArr['accountID'];
			}
			
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getAudioList'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}
	
	public function getProductGroupList() {

			//auslesen aus content_format
			$sql="select"
				." g.product_group_id"
				.",g.parent_group_id as product_parent_group_id"
				.",g.name as product_group_name"
			." from"
				." product_group g"
			." order by"
				." product_group_id"
				.";";
						
			//echo $sql;
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->getProductList'),$this->_dbconn->error);
				return null;
			}
			return $res;
	}
	
		protected function rmdir_recursive($dir) {
			$files = scandir($dir);
			array_shift($files);    // remove '.' from array
			array_shift($files);    // remove '..' from array
		
			foreach ($files as $file) {
				$file = $dir . '/' . $file;
				if (is_dir($file)) {
					rmdir_recursive($file);
					rmdir($file);
				} else {
					unlink($file);
				}
			}
			rmdir($dir);
	}
	
	public function deleteProduction($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['deleteProduction']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
					
			$valueArr = format::$_returnArr;
			
			$sql = "UPDATE production SET delete_ts = now() WHERE production_id = ".$valueArr['productionID'];
			
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
				return null;
			}
			
			return true;
		}
	}
	
	public function adjustHistory() 
	{
		$sql = "select * from history";
		$res = $this->_dbconn->query($sql);
		
		while ($ds = mysqli_fetch_array($res))
		{
			if ($ds['asset_type'] == "clip")
			{
				$history_id = $ds['id'];
				$product_id = $ds['asset_id'];
				
				$sql2 = "select is_dicative from product where product_id = ".(int)$product_id;
				
				$res2 = $this->_dbconn->query($sql2);
				$ds2 = mysqli_fetch_array($res2);
				
				$sql2 = "update history set clip_isDicative = ".$ds2['is_dicative']." where id = ".$history_id;
				$this->_dbconn->query($sql2);
			}
		}
	}
	
    public function healHistory()
    {
        $sql = "SELECT * FROM film WHERE film_id > 5664";

        $res = $this->_dbconn->query($sql);

        while ($ds = mysqli_fetch_array($res))
        {
            $film_id = $ds['film_id'];
            $film_name = $ds['name'];
            $film_date = $ds['update_ts'];
            $account_id = $ds['account_id'];
            $clip_list = $ds['custom1'];
            $motif_list = $ds['custom2'];

            $sql2 = "UPDATE history SET name = '".$film_name."' WHERE asset_id = ".$film_id;
            $sql2 = "INSERT INTO history SET asset_id = ".$film_id
                    .", asset_type = 'film'"
                    .", name = '".$film_name."'"
                    .", account_account_id = ".$account_id
                    .", asset_date = '".$film_date."'"
                    .", asset_timestamp = ".strtotime($film_date);

            if (!$this->_dbconn->query($sql2)) {
                $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
                return null;
            }

            $clips = explode(",", $clip_list);
            $clip_motiflist = explode(".", $motif_list);

            $clip_count = count($clips);

            for ($i=0; $i<$clip_count; $i++)
            {
                $sql2 = "INSERT INTO history SET asset_id = ".$clips[$i]
                    .", asset_type = 'clip'"
                    .", account_account_id = ".$account_id
                    .", asset_date = '".$film_date."'"
                    .", asset_timestamp = ".strtotime($film_date);

                if (!$this->_dbconn->query($sql2)) {
                    $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
                    return null;
                }

                $motifs = explode(",", $clip_motiflist[$i]);
                $motif_count = count($motifs);

                for ($j=0; $j<motif_count; $j++)
                {
                    $sql2 = "INSERT INTO history SET asset_id = ".$motifs[$j]
                        .", asset_type = 'motif'"
                        .", account_account_id = ".$account_id
                        .", asset_date = '".$film_date."'"
                        .", asset_timestamp = ".strtotime($film_date);

                    if (!$this->_dbconn->query($sql2)) {
                        $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
                        return null;
                    }  
                }
            }
        }

    }

	public function updateHistory($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['updateHistory']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
					
			$valueArr = format::$_returnArr;
			
			$this->createPreviewThumbs($valueArr);
			
			$sql = "insert into history set asset_id = ".(int)$valueArr['FilmID']
				 .", asset_type = 'film'"
				 .", account_account_id = ".(int)$valueArr['AccountID']
				 .", name = '".$valueArr['FilmName']."'"
				 .", asset_date = now()"
				 .", asset_timestamp = ".time();
				 
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
				return null;
			}
			
			$clip_ids = explode(',', $valueArr['JobIDList']);
			$motif_lists = explode(',', $valueArr['MotifIDList']);
			$dicative_list = explode(',' ,$valueArr['isDicativeList']);
			$clip_count = count($clip_ids);
			
			for ($i=0; $i<$clip_count; $i++)
			{
				$sql = "insert into history set asset_id = ".(int)$clip_ids[$i]
				 .", asset_type = 'clip'"
				 .", account_account_id = ".(int)$valueArr['AccountID']
				 .", asset_date = now()"
				 .", asset_timestamp = ".time();	
				 
				if (intval($dicativeList[$i]) == 0)
					$sql .= ", clip_isDicative = 0";
				else
					$sql .= ", clip_isDicative = 1";
				 
				if (!$res =  $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
					return null;
				}	
				
				$motif_ids = explode('.', $motif_lists[$i]);
				$motif_count = count($motif_ids);
				 
				for ($j=0; $j<$motif_count; $j++)
				{
					 $sql = "insert into history set asset_id = ".(int)$motif_ids[$j]
					 .", asset_type = 'motif'"
					 .", account_account_id = ".(int)$valueArr['AccountID']
					 .", asset_date = now()"
					 .", asset_timestamp = ".time();	
					 
					if (!$res =  $this->_dbconn->query($sql)) {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
						return null;
					}
				} 
			}
			
			return true;
		}
	}

    protected function serializeCodec($ds)
    {
        return [
            'codec_type_id' => $ds['codec_type_id'],
            'extension'     => $ds['extension'],
            'name'          => $ds['name'],
            'sort_order'    => $ds['sort_order'],
            'type_group'    => $ds['type_group'],
            'width'         => $ds['width'],
            'height'        => $ds['height'],
            'ffmpeg_param'   => $ds['ffmpeg_param']
        ];
    }

    public function getCodecTypes()
    {
        $sql = "SELECT * FROM codec_type";
        $result = [];
        if (!$res = $this->_dbconn->query($sql)) {
            $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
        } else {
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = $this->serializeCodec($ds);
            }
        }

        return $result;
    }

    public function getProductTypes()
    {
        $sql = "SELECT * FROM product_type";
        $result = [];
        if (!$res = $this->_dbconn->query($sql)) {
            $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateHistory'),$this->_dbconn->error);
        } else {
            while ($ds = mysqli_fetch_array($res)) {
                $result[] = [
                    'product_type_id' => $ds['product_type_id'],
                    'name'            => $ds['name'],
                ];
            }
        }

        return $result;
    }

    public function updateProductStatistics($valueArr)
    {                
        $sql =  "INSERT INTO render_time SET"   
                ." product_id = ".(int)$valueArr['productID']
                .",seconds_rendered = ".$valueArr['seconds']
                .",processor_power = ".$valueArr['processorFactor']
                .",std_complexity = ".$valueArr['standardizedComplexity']
                .",filesize = ".$valueArr['filesize'];
      
        if (!$res = $this->_dbconn->query($sql)) {
            $this->_error .= error::buildErrorString(array('SQL-ERROR'=>'production->updateProductStatistics'),$this->_dbconn->error);
        }
        else
        {
            return $res;
        }
        
    }

    public function addSubProduct($valueArr)
    {
        $masterID = (int)$valueArr['masterProductID'];
        $index = (int)$valueArr['index'];
        $first_frame = (int)$valueArr['first_frame'];
        $last_frame = (int)$valueArr['last_frame'];

        $subproduct_id = $masterID+$index;

        $sql = "SELECT * FROM product WHERE product_id = ".$masterID;
        $qryres = $this->_dbconn->query($sql);
        $res = $qryres->fetch_all($resulttype = MYSQLI_ASSOC);
        $res[0]['product_id'] = $subproduct_id;
        $res[0]['master_product_id'] = $masterID;
        $res[0]['name'] = $res[0]['name']." ".$index;
        $res[0]['in_frame'] = $first_frame;
        $res[0]['out_frame'] = $last_frame;
        $res[0]['frame_count'] = ($last_frame - $first_frame) + 1;
		$res[0]['product_type_id'] = 4;
        unset($res[0]['delete_ts']);
        $sql = "INSERT INTO product";
        $sql .= " ( ".implode(",",array_keys($res[0])).") ";
        $sql .= " VALUES ('".implode("','", array_values($res[0])). "')";
        $this->_dbconn->query($sql);   
        $new_subproduct_id = $this->_dbconn->insert_id;

        //get & copy all product -> content associations
        $sql = "SELECT * FROM product_content WHERE product_id = ".$masterID;
        $qryres = $this->_dbconn->query($sql);
        $rows = $qryres->fetch_all($resulttype = MYSQLI_ASSOC);
        while ($row = array_shift($rows))
        {
            unset($row['product_content_id']);
            unset($row['delete_ts']);
            $row['product_id'] = $subproduct_id;
            $sql = "INSERT INTO product_content";
            $sql .= " ( ".implode(",",array_keys($row)).") ";
            $sql .= " VALUES ('".implode("','", array_values($row)). "')";
            $this->_dbconn->query($sql);            
        }

        //get & copy all product -> account_group associations
        $sql = "SELECT * FROM xref_account_group_product WHERE product_id = ".$masterID;
        $qryres = $this->_dbconn->query($sql);
        $rows = $qryres->fetch_all($resulttype = MYSQLI_ASSOC);
        while ($row = array_shift($rows))
        {
            $row['product_id'] = $subproduct_id;
            $sql = "INSERT INTO xref_account_group_product";
            $sql .= " ( ".implode(",",array_keys($row)).") ";
            $sql .= " VALUES ('".implode("','", array_values($row)). "')";
            $this->_dbconn->query($sql);            
        }
        return $subproduct_id;
    }
	
	public function createPreviewThumbs($valueArr) {
		/*
		$path = UPLOAD_DIR . (int)$valueArr['AccountID'].'/productions/'.(int)$valueArr['FilmID'].'/';
		$sourceFile = $path."film_".(int)$valueArr['FilmID']."_preview_hdpi.jpg";
		
		$targetFile = $path."film_".(int)$valueArr['FilmID']."_preview_ldpi.jpg";
		$cmd = 'convert -colorspace rgb -resize 160x90 -quality 100 -density 300 '. $sourceFile .' '. $targetFile;		
		exec($cmd);	

		$targetFile = $path."film_".(int)$valueArr['FilmID']."_preview_mdpi.jpg";
		$cmd = 'convert -colorspace rgb -resize 320x180 -quality 100 -density 300 '. $sourceFile .' '. $targetFile;
		exec($cmd);	
		

		$targetFile = $path."film_".(int)$valueArr['FilmID']."_preview_hdpi.jpg";
		$cmd = 'convert -colorspace rgb -resize 640x360 -quality 100 -density 300 '. $sourceFile .' '. $targetFile;
		exec($cmd);		
		unlink($sourceFile);
		*/
	}
} 
?>