<?php
class Models_Core
{
	//version
	const VERSION = '1.0';
	
	//Error constants begin	异常编号
	//<<<...
	const ERR_BEHAVIOR_ID_NULL 						= -1;	//行为id为空
	const ERR_BEHAVIOR_PROPERTY_WRONG_FORMAT 		= -2;	//行为的属性数组格式错误
	const ERR_BEHAVIOR_LINE_ID_NULL 				= -3;	//行为触发线的id为空
	const ERR_BEHAVIOR_PROPERTY_MARK_WRONG_FORMAT 	= -4;	//行为属性标志数组格式错误
	
	const ERR_DATATYPE_METHOD_NOT_EXIST 			= -5;	//数据类型的判断方法不存在
	const ERR_DATABASE_CONNECT_FAILED 				= -6;	//数据库连接失败
	//...>>>
	//Error constatnts end
	
	//Request State	begin 请求状态
	//<<<...
	/**
	 * 
	 * 请求成功
	 * @var string
	 */
	const STATE_REQUEST_SUCCESS 									= 1;
	/**
	 * 
	 * 没有登录
	 * @var string
	 */
	const STATE_NOT_LOGIN											= 10000;
	/**
	 *
	 * 逻辑服务器与数据库连接出现错误
	 * @var string
	 */
	const STATE_DB_ERROR											= 10001;

	/**
	 * 
	 * 行为：注册参数(既不是邮箱地址，也不是手机号)不正确
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_REGISTERINFO_INVALID				= 2;
	/**
	 * 
	 * 行为：注册参数缺少
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_MISS_PARAMETER 					= 3;
	/**
	 * 
	 * 行为：邮箱地址已经被注册了
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_EMAIL_REGISTERED 					= 4;	
	/**
	 * 
	 * 行为：邮箱地址注册，待确认
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_EMAIL_REGISTERING 				= 5;	
	/**
	 * 
	 * 行为: 确认邮箱注册时，缺少确认码
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_CONFIRM_EMAIL_MISS_PARAMETER 		= 6;	
	/**
	 * 
	 * 行为：确认码不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_CONFIRM_EMAIL_VERIFYCODE_INAVALID = 7;	
	/**
	 * 
	 * 行为：手机号已经被注册
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_TELEPHONE_REGISTERED				= 8;
	/**
	 * 
	 * 行为：受时间限制，不能重复发确认短信
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_TELEPHONE_MSG_TIME_RESTRICT		= 9;	
	
	/**
	 * 
	 * 行为：登陆参数（既不是邮箱地址，也不是手机号）不正确
	 * @var string
	 */
	const STATE_BEHAVIOR_LOGIN_LOGININFO_INVALID					= 10;	
	/**
	 * 
	 * 行为：登陆失败，用户名不存在或者密码错误
	 * @var string
	 */
	const STATE_BEHAVIOR_LOGIN_USER_OR_SECRET_WRONG					= 11;	
	
	/**
	 * 
	 * 行为：设置头像，缺少参数（用户id：没有登录，头像：没有上传头像照片）
	 * @var string
	 */
	const STATE_BEHAVIOR_SETAVATAR_MISS_PARAMETER					= 12;	
	/**
	 * 
	 * 行为：设置头像，用户id为UserId的用户不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_SETAVATAR_USERID_UNEXIST					= 13;	
	
	/**
	 * 
	 * 行为：创建游记，缺少参数
	 * @var string
	 */
	const STATE_BEHAVIOR_CREATEJOURNAL_MISS_PARAMETER				= 18;	
	/**
	 * 
	 * 行为：创建游记，参数不符合规格（usr_id不存在）
	 * @var string
	 */
	const STATE_BEHAVIOR_CREATEJOURNAL_PARAMETER_INVALID			= 19;	

	/**
	 * 
	 * 行为：添加游记内容，缺少参数（用户id、journalPlaceId或者infoText）
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDJOURNALPLACEINFO_MISS_PARAMETER			= 23;
	/**
	 * 
	 * 行为：添加游记内容，参数不符合规格（usr_id或者journalPlaceId不存在）
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDJOURNALPLACEINFO_PARAMETER_INVALID		= 24;
	
	/**
	 * 
	 * 行为：添加游记照片，缺少参数（用户id、journalPlaceId或者photo）
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDPHOTOTOJOURNALINFO_MISS_PARAMETER		= 25;	
	/**
	 * 
	 * 行为：添加游记照片，参数不符合规格（属于usrId的journalPlaceInfoId不存在）
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDPHOTOTOJOURNALINFO_PARAMETER_INVALID	= 26;	
	/**
	 * 
	 * 行为：添加游记图片，图片格式不对
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDPHOTOTOJOURNALINFO_PHOTO_INVALID		= 27;	
	
	/**
	 * 
	 * 行为：收藏游记，缺少参数
	 * @var string
	 */
	const STATE_BEHAVIOR_FAVOURITEJOURNAL_MISS_PARAMETER			= 28;	
	/**
	 * 
	 * 行为：收藏游记，参数不合理，journalid或者Usrid不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_FAVOURITEJOURNAL_PAAMETER_INVALID			= 29;	
	
	/**
	 * 
	 * 行为：收藏游记景点，缺少参数
	 * @var string
	 */
	const STATE_BEHAVIOR_FAVOURITEJOURNALPLACE_MISS_PARAMETER		= 30;	
	/**
	 * 
	 * 行为：收藏游记景点，参数不合理，journalPlaceId或者UserId不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_FAVOURITEJOURNALPLACE_PARAMENTER_INVALID	= 31;	
	
	/**
	 * 
	 * 行为：创建游记景点，缺少参数（用户id、journalId或者place）
	 * @var string
	 */
	const STATE_BEHAVIOR_CREATEJOURNALPLACE_MISS_PARAMETER			= 20;	
	/**
	 * 
	 * 行为：创建游记景点，参数不符合规格（usr_id、journal_id或者place不存在）
	 * @var string
	 */
	const STATE_BEHAVIOR_CREATEJOURNALPLACE_PARAMETER_INVALID		= 21;	
	/**
	 * 
	 * 行为：创建游记景点，景点不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_CREATEJOURNALPLACE_PLACE_INVALID			= 22;	
	
	/**
	 * 
	 * 行为：添加景点， 缺少参数（用户id、经度、纬度、景点名字）
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDPLACE_MISS_PARAMETER					= 34;	
	/**
	 * 
	 * 行为：添加景点，通过坐标无法确定到locality的范围
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDPLACE_LATLNG_INVALID					= 35;
	
	/**
	 * 
	 * 行为：删除景点，缺少参数（用户id、景点id）
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEPLACE_MISS_PARAMETER					= 36;
	/**
	 * 
	 * 行为：删除景点，景点id不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEPLACE_PLACEID_INVALID				= 37;
	
	/**
	 * 
	 * 行为：添加景点标签，缺少参数（用户id、景点id、标签文字）
	 * @var string
	 */
	const STATE_BEHAVIOR_ADDPLACETAG_MISS_PARAMETER					= 46;
	
	/**
	 * 
	 * 行为：认同景点标签，缺少参数（用户id、景点标签id）
	 * @var string
	 */
	const STATE_BEHAVIOR_AGREETAG_MISS_PARAMETE						= 47;
	/**
	 * 
	 * 行为：认同景点标签，景点标签id不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_AGREETAG_PLACETAGID_INVALID				= 48;
	
	/**
	 * 
	 * 行为：评论游记，缺少参数（用户id、游记id、评论内容）
	 * @var string
	 */
	const STATE_BEHAVIOR_COMMENTJOURNAL_MISS_PARAMETER				= 63;
	
	/**
	 * 
	 * 行为：评论游记景点，缺少参数（用户id、游记景点id、评论内容）
	 * @var string
	 */
	const STATE_BEHAVIOR_COMMENTJOURNALPLACE_MISS_PARAMETER			= 64;
	
	/**
	 * 
	 * 行为：删除游记，缺少参数（用户id、游记id）
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNAL_MISS_PARAMETER				= 70;
	/**
	 * 
	 * 行为：删除游记，没有权限
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNAL_NO_PRIVILEGE					= 71;
	
	/**
	 * 
	 * 行为：删除游记景点，缺少参数（游记id、游记景点id）
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALPLACE_MISS_PARAMETER			= 72;
	/**
	 * 
	 * 行为：删除游记景点，没有权限
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALPLACE_NO_PRIVILEGE			= 73;
	
	/**
	 * 
	 * 行为：设置游记是否为私人游记，缺少参数（游记id、游记属性）
	 * @var string
	 */
	const STATE_BEHAVIOR_SETJOURNALPRIVACY_MISS_PARAMETER			= 74;
	/**
	 * 
	 * 行为：设置游记是否为私人游记，没有权限
	 * @var string
	 */
	const STATE_BEHAVIOR_SETJOURNALPRIVACY_NO_PRIVILEGE				= 75;
	
	/**
	 * 
	 * 行为：设置签名，缺少参数（用户id、签名）
	 * @var string
	 */
	const STATE_BEHAVIOR_SETSAYING_MISS_PARAMETER					= 80;
	
	/**
	 * 
	 * 行为：设置新密码，缺少参数（用户id、新密码）
	 * @var string
	 */
	const STATE_BEHAVIOR_SETNEWPASSWORD_MISS_PARAMETER				= 81;
	/**
	 * 
	 * 行为：设置新密码，旧密码错误
	 * @var string
	 */
	const STATE_BEHAVIOR_SETNEWPASSWORD_OLDPASSWORD_WRONG			= 82;
	
	/**
	 * 
	 * 行为：关注驴友，缺少参数（关注用户id、被关注的用户id）
	 * @var string
	 */
	const STATE_BEHAVIOR_FOLLOWUSERBYID_MISS_PARAMETER				= 83;
	/**
	 * 
	 * 行为：关注驴友，已经关注过
	 * @var string
	 */
	const STATE_BEHAVIOR_FOLLOWUSERBYID_FOLLOWED					= 84;
	
	/**
	 * 
	 * 行为：关注驴友，缺少参数（关注用户id、被关注用户的绑定手机号）
	 * @var string
	 */
	const STATE_BEHAVIOR_FOLLOWUSERBYTELEPHONE_MISS_PARAMETER		= 85;
	/**
	 * 
	 * 行为：关注驴友，已经关注过
	 * @var string
	 */
	const STATE_BEHAVIOR_FOLLOWUSERBYTELEPHONE_FOLLOWED				= 86;
	
	/**
	 * 
	 * 行为：绑定邮箱，缺少参数（用户id、邮箱地址）
	 * @var string
	 */
	const STATE_BEHAVIOR_BINDEMAIL_MISS_PARAMETER					= 87;
	/**
	 * 
	 * 行为：绑定邮箱，已经绑定过其他邮箱了
	 * @var string
	 */
	const STATE_BEHAVIOR_BINDEMAIL_BINDED							= 88;
	/**
	 * 
	 * 行为：绑定邮箱，用户不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_BINDEMAIL_USERID_INVALID					= 89;
	
	/**
	 * 
	 * 行为：绑定手机号，缺少参数（用户id、手机号）
	 * @var string
	 */
	const STATE_BEHAVIOR_BINDMOBILE_MISS_PARAMETER					= 90;
	/**
	 * 
	 * 行为：绑定手机号，已经绑定过其他手机号了
	 * @var string
	 */
	const STATE_BEHAVIOR_BINDMOBILE_BINDED							= 91;
	/**
	 * 
	 * 行为：绑定手机号，用户不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_BINDMOBILE_USERID_INVALID					= 92;
	
	/**
	 * 
	 * 行为：确认注册，缺少参数（确认码）
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_CONFIRM_TELEPHONE_BY_CODE_MISS_PARAMETER	= 93;
	/**
	 * 
	 * 行为：确认注册，没有匹配的注册信息
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_CONFIRM_TELEPHONE_BY_CODE_UNEXIST	= 96;
	
	/**
	 * 
	 * 行为：确认注册，缺少参数（手机号、短信内容）
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_CONFIRM_TELEPHONE_BY_MSG_MISS_PARAMETER	= 94;
	/**
	 * 
	 * 行为：确认注册，用户发短信拒绝注册
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_CONFIRM_TELEPHONE_BY_MSG_REFUSE	= 95;
	/**
	 * 
	 * 行为：确认注册，没有匹配的注册信息
	 * @var string
	 */
	const STATE_BEHAVIOR_REGISTER_CONFIRM_TELEPHONE_BY_MSG_UNEXIST	= 97;
	
	/**
	 * 
	 * 行为：取消关注驴友，缺少参数（用户id， 取消关注的驴友的id）
	 * @var string
	 */
	const STATE_BEHAVIOR_UNFOLLOWUSERBYID_MISS_PARAMETER			= 98;
	/**
	 * 
	 * 行为：取消关注驴友，驴友不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_UNFOLLOWUSERBYID_USER_UNEXIST				= 99;
	
	/**
	 * 
	 * 行为：取消收藏游记， 缺少参数（用户id、游记id）
	 * @var string
	 */
	const STATE_BEHAVIOR_UNFAVOURITEJOURNAL_MISS_PARAMETER			= 100;	
	
	/**
	 * 
	 * 行为：取消收藏游记景点， 缺少参数（用户id、游记景点id）	
	 * @var string
	 */
	const STATE_BEHAVIOR_UNFAVOURITEJOURNALPLACE_MISS_PARAMETER		= 101;	
	
	/**
	 * 
	 * 行为：删除游记内容，缺少参数（用户id、游记内容id）
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALPLACEINFO_MISS_PARAMETER		= 102;
	/**
	 * 
	 * 行为：删除游记内容，内容不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALPLACEINFO_INFOID_UNEXIST		= 103;
	
	/**
	 * 
	 * 行为：删除游记图片，缺少参数（用户id、图片id）
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEINFOPHOTO_MISS_PARAMETER				= 104;
	/**
	 * 
	 * 行为：删除游记图片，图片不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEINFOPHOTO_PHOTO_UNEXIST				= 105;
	
	/**
	 * 
	 * 行为：删除游记评论，缺少参数（用户id、评论id）
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALCOMMENT_MISS_PARAMETER		= 106;
	/**
	 * 
	 * 行为：删除游记评论，评论不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALCOMMENT_COMMENT_UNEXIST		= 107;	
	
	/**
	 * 
	 * 行为：删除游记景点评论，缺少参数（用户id、评论id）
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALPLACECOMMENT_MISS_PARAMETER	= 108;	
	/**
	 * 
	 * 行为：删除游记景点评论，评论不存在
	 * @var string
	 */
	const STATE_BEHAVIOR_DELETEJOURNALPLACECOMMENT_COMMENT_UNEXIST	= 109;
	
	/**
	 * 
	 * 数据：位置信息所属用户的id为空
	 * @var string
	 */
	const STATE_DATA_GETUSERPOSITION_REQUESTUSERID_NULL				= 14;
	/**
	 * 
	 * 数据：用户的位置信息不公开
	 * @var string
	 */
	const STATE_DATA_GETUSERPOSITION_USER_POSITION_SECRET			= 15;
	
	/**
	 * 
	 * 数据：用户信息所属用户的id为空
	 * @var string
	 */
	const STATE_DATA_GETUSERINFO_REQUESTUSERID_NULL					= 16;
	/**
	 * 
	 * 数据：不存在id为usrId的用户
	 * @var string
	 */
	const STATE_DATA_GETUSERINFO_USERID_INVALID						= 17;
	
	/**
	 * 
	 * 数据：获取好友动态：不存在动态
	 * @var string
	 */
	const STATE_DATA_GETFRIENDMOVEMENT_NO_JOURNAL_EXIST				= 32;
	
	/**
	 * 
	 * 数据：获取游记属性，权限不足
	 * @var string
	 */
	const STATE_DATA_GETJOURNALINFO_NO_PERMISSIONS					= 38;	
	
	/**
	 * 
	 * 数据：获取游记中的游记景点，没有创建新的游记景点
	 * @var string
	 */
	const STATE_DATA_GETJOURNALPLACES_NO_JOURNALPLACE				= 39;
	
	/**
	 * 
	 * 数据：获取游记景点的内容，没有内容
	 * @var string
	 */
	const STATE_DATA_GETJOURNALPLACEINFO_NO_INFO					= 40;
	
	/**
	 * 
	 * 数据：获取游记内容的图片，没有图片
	 * @var string
	 */
	const STATE_DATA_GETJOURNALINFOPHOTO_NO_PHOTO					= 41;
	
	/**
	 * 
	 * 数据：搜索周边景点，没有景点
	 * @var string
	 */
	const STATE_DATA_SEARCHPLACE_NO_PLACE							= 42;
	
	/**
	 * 
	 * 数据：获取景点信息，景点id不存在 
	 * @var string
	 */
	const STATE_DATA_GETPLACEINFO_PLACEID_INVALID					= 43;
	
	/**
	 * 
	 * 数据：获取热门城市，没有合适的结果
	 * @var string
	 */
	const STATE_DATA_GETCURRENTHOTCITYS_ZERO_RESULT					= 44;
	
	/**
	 * 
	 * 数据：根据月份获取热门景点，没有结果
	 * @var string
	 */
	const STATE_DATA_GETPLACESBYMONTH_ZERO_RESULT					= 45;
	
	/**
	 * 
	 * 数据：获取旅游的城市，没有结果
	 * @var string
	 */
	const STATE_DATA_GETJOURNALCITYS_ZERO_RESULT					= 49;
	
	/**
	 * 
	 * 数据：通过标签搜索景点，没有结果
	 * @var string
	 */
	const STATE_DATA_GETPLACESBYTAG_ZERO_RESULT						= 50;
	
	/**
	 * 
	 * 数据：通过景点标志搜索景点，没有结果
	 * @var string
	 */
	const STATE_DATA_GETPLACESBYHOTTAG_ZERO_RESULT 					= 51;
	
	/**
	 * 
	 * 数据：搜搜周边景点，没有结果
	 * @var string
	 */
	const STATE_DATA_GETRIMPLACES_ZERO_PLACES						= 52;
	
	/**
	 * 
	 * 数据：获取用户收藏游记的列表的时候，用户的id为null
	 * @var string
	 */
	const STATE_DATA_GETFAVOURITEJOURNAL_REQUESTUSRID_NULL			= 53;
	/**
	 * 
	 * 数据：获取用户收藏游记的列表的时候，收藏列表为空
	 * @var string
	 */
	const STATE_DATA_GETFAVOURITEJOURNAL_ZERO_RESULT				= 54;
	
	/**
	 * 
	 * 数据：获取用户收藏游记景点的列表时，用户的id为null
	 * @var string
	 */
	const STATE_DATA_GETFAVOURITEJOURNALPLACE_REQUESTUSRID_NULL		= 55;
	/**
	 * 
	 * 数据：获取用户收藏游记景点的列表时，收藏列表为空
	 * @var string
	 */
	const STATE_DATA_GETFAVOURITEJOURNALPLACE_ZERO_RESULT			= 56;
	
	/**
	 * 
	 * 数据：获取用户游记列表，用户id为null
	 * @var string
	 */
	const STATE_DATA_GETJOURNAL_REQUESTUSRID_NULL					= 57;
	/**
	 * 
	 * 数据：获取用户游记列表，没有游记
	 * @var string
	 */
	const STATE_DATA_GETJOURNAL_ZERO_RESULT							= 58;
	
	/**
	 * 
	 * 数据：以城市为目的地获取游记，城市id为null
	 * @var string
	 */
	const STATE_DATA_GETJOURNALBYCITY_CITYID_NULL					= 59;
	/**
	 * 
	 * 数据：以城市为目的地获取游记，没有返回结果
	 * @var string
	 */
	const STATE_DATA_GETJOURNALBYCITY_ZERO_RESULT					= 60;
	
	/**
	 * 
	 * 数据：查询某景点的游记景点，没有返回结果
	 * @var string
	 */
	const STATE_DATA_GETJOURNALBYPLACE_ZERO_RESULT					= 61;
	
	/**
	 * 
	 * 数据：获得特殊推荐游记景点，没有返回结果
	 * @var string
	 */
	const STATE_DATA_GETSPECIALJOURNAL_ZERO_RESULT					= 62;
	
	/**
	 * 
	 * 数据：获取游记评论，缺少参数（用户id、游记id）
	 * @var string
	 */
	const STATE_DATA_GETJOURNALCOMMENT_MISS_PARAMETER				= 66;	
	/**
	 * 
	 * 数据：获取游记评论，没有结果
	 * @var string
	 */
	const STATE_DATA_GETJOURNALCOMMENT_ZERO_RESULT					= 67;
	
	/**
	 * 
	 * 数据：获取游记景点评论，缺少参数（用户id、游记景点id）
	 * @var string
	 */
	const STATE_DATA_GETJOURNALPLACECOMMENT_MISS_PARAMETER			= 68;
	/**
	 * 
	 * 数据：获取游记景点评论，没有结果
	 * @var string
	 */
	const STATE_DATA_GETJOURNALPLACECOMMENT_ZERO_RESULT				= 69;
	
	/**
	 * 
	 * 数据：获取周边驴友列表，缺少参数（用户id、经度、纬度、距离、beginIndex、rowCount）
	 * @var string
	 */
	const STATE_DATA_GETCLOSEUSER_MISS_PARAMETER					= 76;
	/**
	 * 
	 * 数据：获取周边驴友列表，没有结果
	 * @var string
	 */
	const STATE_DATA_GETCLOSEUSER_ZERO_RESULT						= 77;
	
	/**
	 * 
	 * 数据：获取用户头像，用户id不存在
	 * @var string
	 */
	const STATE_DATA_GETUSERBIGAVATAR_USERID_INVALID				= 78;
	
	/**
	 * 
	 * 数据：获取用户头像，用户id不存在
	 * @var string
	 */
	const STATE_DATA_GETUSERSMALLAVATAR_USERID_INVALID				= 79;	
	
	/**
	 * 
	 * 数据：根据关键字在城市内搜索景点，缺少参数（关键字、城市名）
	 * @var string
	 */
	const STATE_DATA_SEARCHPLACEINCITY_MISS_PARAMETER				= 110;
	/**
	 * 
	 * 数据：根据关键字在城市内搜索景点，没有结果
	 * @var string
	 */
	const STATE_DATA_SEARCHPLACEINCITY_ZERO_RESULT					= 111;	
	
	/**
	 * 
	 * 数据：根据关键字在区内搜索景点，缺少参数（关键字、区名）
	 * @var string
	 */
	const STATE_DATA_SEARCHPLACEINSUBLOCALITY_MISS_PARAMETER		= 112;
	/**
	 * 
	 * 数据：根据关键字在区内搜索景点，没有结果
	 * @var string
	 */
	const STATE_DATA_SEARCHPLACEINSUBLOCALITY_ZERO_RESULT			= 113;
	
	/**
	 * 
	 * 数据：根据关键字搜索周围的景点，缺少参数（关键字、经度、纬度、半径）
	 * @var string
	 */
	const STATE_DATA_SEARCHPLACENEARBY_MISS_PARAMETER				= 114;
	/**
	 * 
	 * 数据：根据关键字搜索周围的景点，没有结果
	 */
	const STATE_DATA_SEARCHPLACENEARBY_ZERO_RESULT					= 115;
	
	/**
	 * 
	 * 数据：搜索当前位置的候选地理信息，缺少调用参数
	 * @var string
	 */	
	const STATE_DATA_GETCURADDRESS_MISS_PARAMETER					= 116;
	/**
	 * 
	 * 数据：搜索当前位置的候选地理信息，没有结果
	 * @var string
	 */	
	const STATE_DATA_GETCURADDRESS_ZERO_RESULT						= 117;	
	
	/**
	 * 
	 * 数据：获得用户最新修改的游记的id，参数（用户id）为空
	 * @var string
	 */
	const STATE_DATA_GETLATESTJOURNAL_USERID_NULL					= 118;
	/**
	 * 
	 * 数据：获得用户最新修改的游记的id，该用户没有创建游记
	 * @var string
	 */
	const STATE_DATA_GETLATESTJOURNAL_ZERO_RESULT					= 119;
	
	/**
	 * 
	 * 数据：获取照片，照片版本未知
	 * @var string
	 */
	const STATE_DATA_GETPHOTO_VERSION_UNKNOWN						= 120;
	/**
	 * 
	 * 数据：获取照片，照片id未知
	 * @var string
	 */
	const STATE_DATA_GETPHOTO_PHOTOID_UNKNOWN						= 121;
	/**
	 * 
	 * 数据：获取照片，照片不存在
	 * @var string
	 */
	const STATE_DATA_GETPHOTO_PHOTO_UNEXIST							= 122;
	//...>>>
	//Request State end
	
	//Memcached Server begin
	//<<<...
	static $MEMCACHE_SERVER_MAP = array(
		array(
			'host'			=> '192.168.1.105',
			'port'			=> 10000,
			'persistent'	=> true
		),	
		array(
			'host'			=> '192.168.1.105',
			'port'			=> 10001,
			'persistent'	=> true
		),
		array(
			'host'			=> '192.168.1.105',
			'port'			=> 10002,
			'persistent'	=> true
		)
	);
	//...>>>
	//Memcached Server end
	
	//Email Server Configure begin
	//<<<...
	const EMAIL_CHARSET				= 'utf-8';						//邮件字符集
	const EMAIL_AUTH_MODE			= 'login';						//身份认证模式
	const EMAIL_SMTP_SERVER			= 'smtp.exmail.qq.com';			//smtp服务器地址
	const EMAIL_SERVER_ANONYMOUS	= 'Sunnyfarmers';				//发件人的昵称
	const EMAIL_USERNAME 			= 'skyliang@ugshare.com';		//邮件发送服务器用户名
	const EMAIL_PASSWORD 			= 'q19890201';					//密码
	const EMAIL_TO_ANONYMOUS 		= 'lvyou';
	const EMAIL_TO_TEST1  			= 'brother0001@ugshare.com';			//测试用的发送邮箱地址
	const EMAIL_TO_TEST2			= 'brother0002@ugshare.com';		
	//...>>>
	//Email Server Configure end
	
	//SMS Server Configuration begin
	//<<<...
	const SMS_SMSGATE_USERNAME		= 'skyliang';
	const SMS_SMSGATE_PWD			= '19890201';
	//...>>>
	//SMS Server Configuration end
	
	//Avatar Detail begin
	//<<<...
	const AVATAR_BIG_WIDTH			= 128;
	const AVATAR_BIG_HEIGHT			= 128;
	const AVATAR_SMALL_WIDTH		= 32;
	const AVATAR_SMALL_HEIGHT		= 32;
	//...>>>
	//Avatar Detaril end
	//Photo Detail begin
	//<<<...
	const PHOTO_PC_LIMIT_WIDTH		= 512;
	const PHOTO_PC_LIMIT_HEIGHT		= 600;
	const PHOTO_MOBILE_LIMIT_WIDTH	= 512;
	const PHOTO_MOBILE_LIMIT_HEIGHT	= 600;
	const PHOTO_SMALL_LIMIT_WIDTH	= 48;
	const PHOTO_SMALL_LIMIT_HEIGH	= 60;
	
	const PHOTO_TAG_FONT_FILE		= 'STHUPO.TTF';
	const PHOTO_TAG					= 'UGShare';
	const PHOTO_TAG_FONT_SIZE		= 10;
	//...>>>
	//photo Detail end
	
	//Movement Type begin
	//type的定义，写入了数据库的触发器，不要改动*******************
	//<<<...
	const MOVEMENT_TYPE_CREATE_JOURNAL 				= 1;
	const MOVEMENT_TYPE_CREATE_JOURNAL_PLACE 		= 2;
	const MOVEMENT_TYPE_CREATE_JOURNAL_INFO 		= 3;
	const MOVEMENT_TYPE_FAVOURITE_JOURNAL 			= 4;
	const MOVEMENT_TYPE_FAVOURITE_JOURNAL_PLACE 	= 5;
	const MOVEMENT_TYPE_HOT_JOURNAL					= 6;
	//...>>>
	//Movement Type end
	
	//Database connection configure begin
	//<<<...
	private static $DSN 						= 'mysql:dbname=db;host=127.0.0.1';
	private static $USRNAME 					= 'root';
	private static $PASSWORD 					= '19890201';
	private static $dbh 						= null;						//PDO对象
	private static $DOCTRINE_CONNECTION_NAME 	= 'doctrineConn';
	//...>>>
	//Database connection configure end
	
	//User Movement Type begin
	//<<...
	const USER_MOVEMENT_TYPE_CREATE_JOURNAL				= 1;			//驴友动态类型：创建新游记
	const USER_MOVEMENT_TYPE_EDIT_JOURNAL				= 2;			//驴友动态类型：修改游记
	const USER_MOVEMENT_TYPE_CREATE_JOURNAL_PLACE		= 3;			//驴友动态类型：创建新游记景点
	const USER_MOVEMENT_TYPE_EDIT_JOURNAL_PLACE			= 4;			//驴友动态类型：编辑新游记景点
	const USER_MOVEMENT_TYPE_FAVOURITE_JOURNAL			= 5;			//驴友动态类型：收藏游记
	const USER_MOVEMENT_TYPE_FAVOURITE_JOURNAL_PLACE	= 6;			//驴友动态类型：收藏游记景点
	//...>>
	//User Movement Type end
	
	//SCORE BEGIN
	//<<<...
	const SCORE_JOURNAL_LEAST_MARKCOUNT					= -1;
	const SCORE_JOURNALPLACE_LEAST_MARKCOUNT			= -1;
	const SCORE_PLACE_LEAST_MARKCOUNT					= -1;
	//...>>>
	//SCORE END
	public static function getPDO()
	{
		if (!self::$dbh)
		{
			self::$dbh = new PDO(self::$DSN, self::$USRNAME, self::$PASSWORD);
		}
		return self::$dbh;
	}
	
	public static function getDoctrineConn()
	{
		$dbh = Models_Core::getPDO();	//获取PDO对象
		if (!$dbh)
		{
			throw new Models_Exception(Models_Core::ERR_DATABASE_CONNECT_FAILED);
			return false;
		}

		//设置Doctrine的连接， 首先通过connection获取当前的数据库连接；如果为空，那么设置新的连接
		try
		{
			$conn = Doctrine_Manager::connection();
		}
		catch(Doctrine_Connection_Exception $e)
		{
			$conn = Doctrine_Manager::connection($dbh , self::$DOCTRINE_CONNECTION_NAME);
		}
		return $conn;
	}
	
	public static function initSession()
	{
		static $isSessinStart = false;
		if (!$isSessinStart)
		{
			session_start();
			$isSessinStart = true;
		}
		
	}
}
