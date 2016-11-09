<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*******************************************************************/

// Functions


/**
* Escapes input/output based on type of escape
* @param string $data The string to sanitize
* @param string $method What type of sanitizing we should do
* @return string The sanitized string
*/
function epic_data_escape( $data, $method = "attr" ){
	switch($method){
		case "attr":
			$escape_data = esc_attr($data);
			break;
		case "strip":
			$escape_data = wp_strip_all_tags($data);
			break;
		case 'kses':
			//$data = nl2br($data);
			$escape_data = strip_tags($data, '<a><img><p><br><i><b><u><em><strong><strike><font><span><div>');
			break;
		case "textarea":
			$data = str_replace("<br />", "", $data);
			$escape_data = esc_textarea($data);
			break;
		case "html":
			$escape_data = esc_html($data);
			break;
		case "url":
			$escape_data = esc_url($data);
			break;
		case "raw_url":
			$escape_data = esc_url_raw($data);
			break;
		case "specialchars_decode":
			$escape_data = wp_specialchars_decode($data, ENT_QUOTES);
			$escape_data = strip_tags($escape_data);
			break;			
	}
	$escape_data = trim($escape_data);
	$escape_data = stripslashes($escape_data);
	
	return $escape_data;
}

/**
* Creates a hash for a string
* @param string $string The string to hash
* @param string $algorithm The type of hash
* @param string $secret The hash secret
* @param string $attach Attach the string to the hashed string
* @return string The hashed string
*/
function epic_string_hash( $string, $algorithm = "md5", $secret = "secret", $attach = true ){
	$hash_string = hash_hmac($algorithm, $string, $secret);
	if( $attach )
		$hash_string .= $string;
		
	return $hash_string;
}

/**
* Strip a string from a hash
* @param string $has_string The hashed string
* @param string $characters The number of front-loaded characters
* @return string The unhashed string
*/
function epic_string_unhash( $hash_string, $characters = 32 ){
	return substr($hash_string, $characters);
}


/**
 * simple method to encrypt or decrypt a plain text string
 * initialization vector(IV) has to be the same when encrypting and decrypting
 * PHP 5.4.9
 *
 * this is a beginners template for simple encryption decryption
 * before using this in production environments, please read about encryption
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 *
 * @return string
 */
function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key epic';
    $secret_iv = 'This is my secret iv epic';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

/**
 * Returns requests information regarding the logged-in user
 * @param string $specific The specific user parameter to retrieve.
 */
function epic_current_user( $specific = false ){
    global $wpdb;
    $u = wp_get_current_user();
    if( !$specific )
        return $u;

    switch($specific){
        case 'id':
            $column = intval($u->ID);
            break;
        case 'login':
            $column = epic_data_escape($u->user_login, "strip");
            break;
        case 'email':
            $column = $u->user_email;
            break;
        case 'level':
            $user_level = get_user_meta($u->ID, $wpdb->prefix."user_level", true); //print_r($u);
            $column = intval($user_level);
            break;
        case 'name':
            $column = etf_data_escape($u->display_name, "strip");
            break;
    }

    return $column;
}

/**
 * Build pagination links
 */
function epic_content_global_pagination($page, $data_arr, $permalink, $jump_to = false){
    if( !$data_arr['data'] || $data_arr['max_page'] <= 1 ) return false;

    $join = (strstr($permalink,"?")?"&":"?"); ?>
    <nav role="navigation">
    <ul class="cd-pagination  no-space"><?php
    if( $data_arr['paged'] > 1){ ?>
        <li class="buttonp"><a href="<?php echo epic_data_escape($permalink."{$join}pg=".($page-1), "url") ?>">Previous</a></li><?php
    }
    for( $i = 1; $i <= $data_arr['max_page']; $i++ ){
        if( ($i < ($page-3) || $i > ($page+3)) ) continue;
        if( ($i < ($page-2) || $i > ($page+2)) ){ ?>
            <li class="disabled"><span><a href="#">...</a></span></li><?php
            continue;
        } ?>
    <li <?php if( $page == $i ) echo 'class="current"' ?>><a href="<?php echo epic_data_escape($permalink."{$join}pg=".intval($i), "url") ?>"><?php echo intval($i) ?></a></li><?php
    }
    if( $data_arr['max_page'] > $data_arr['paged'] ){ ?>
        <li class="buttonp"><a href="<?php echo epic_data_escape($permalink."{$join}pg=".($page+1), "url") ?>">Next </a></li><?php
    } ?>
    </ul><?php
    if( $jump_to ){ ?>
        <div class="clear"></div><div class="margin-top-10"></div>
        Page
        <select name="content-pagination-<?php echo $jump_to ?>" class="margin-right-10" style="width: 100px; margin-bottom: 0px;"><?php
            for( $x = 1; $x <= $data_arr['max_page']; $x++ ){ ?>
                <option value="<?php echo intval($x).$query_string ?>" <?php if( $page == $x ) echo 'selected="selected"'; ?>><?php echo intval($x) ?></option><?php
            } ?>
        </select>
        <script type="text/javascript">
            var jq = jQuery;
            jq('body').find("select[name='content-pagination-<?php echo $jump_to ?>']").change(function(e){
                var page = jq(this).val();
                var location = window.location.href;
                var url_pieces = location.split("?");
                window.location.href = url_pieces[0]+"?pg="+page;
            });
        </script><?php
    } ?>
    </nav><?php
}


/**
 * Returns the display name for the user
 * @param bool|int $user_id The ID of the user to display the name for.
 * @param bool $contact_only Whether or not to display the user's login if no display name exists
 * @return bool|string The display name for the given user ID
 */
function epic_user_display_name( $user_id = false, $contact_only = false, $no_loggedin = false ){
    // Only use logged-in user if $no_loggedin is conditionally correct
    if( $no_loggedin && !$user_id ){
        return "Anonymous";
    }
    /*display the retrieved name if checking a logged-in user*/
    $user = epic_current_user();
    if( !$user_id && $user->display_name )
        return $user->display_name;
    elseif( !$user_id && !$contact_only )
        return $user->user_login;

    /*if looking for contact name only, return false*/
    if( $contact_only )
        return false;

    $user = get_userdata( $user_id );
    if( $user && $user->display_name )
        return $user->display_name;
    elseif( $user && !$contact_only )
        return $user->user_login;

    return false;
}

function epic_generate_random( $length = 20 ){
    $code = uniqid(mt_rand(), true);
    $hash = sha1($code);
    return substr($hash, 0, $length);
}


/**
 * Get all countries data
 * @return array
 */
function epic_get_countries(){
    $data = array(
        'AF' => __( 'Afghanistan' ),
        'AL' => __( 'Albania' ),
        'DZ' => __( 'Algeria' ),
        'AS' => __( 'American Samoa' ),
        'AD' => __( 'Andorra' ),
        'AO' => __( 'Angola' ),
        'AI' => __( 'Anguilla' ),
        'AQ' => __( 'Antarctica' ),
        'AG' => __( 'Antigua and Barbuda' ),
        'AR' => __( 'Argentina' ),
        'AM' => __( 'Armenia' ),
        'AW' => __( 'Aruba' ),
        'AU' => __( 'Australia' ),
        'AT' => __( 'Austria' ),
        'AZ' => __( 'Azerbaijan' ),
        'BS' => __( 'Bahamas' ),
        'BH' => __( 'Bahrain' ),
        'BD' => __( 'Bangladesh' ),
        'BB' => __( 'Barbados' ),
        'BY' => __( 'Belarus' ),
        'BE' => __( 'Belgium' ),
        'BZ' => __( 'Belize' ),
        'BJ' => __( 'Benin' ),
        'BM' => __( 'Bermuda' ),
        'BT' => __( 'Bhutan' ),
        'BO' => __( 'Bolivia' ),
        'BA' => __( 'Bosnia and Herzegovina' ),
        'BW' => __( 'Botswana' ),
        'BV' => __( 'Bouvet Island' ),
        'BR' => __( 'Brazil' ),
        'BQ' => __( 'British Antarctic Territory' ),
        'IO' => __( 'British Indian Ocean Territory' ),
        'VG' => __( 'British Virgin Islands' ),
        'BN' => __( 'Brunei' ),
        'BG' => __( 'Bulgaria' ),
        'BF' => __( 'Burkina Faso' ),
        'BI' => __( 'Burundi' ),
        'KH' => __( 'Cambodia' ),
        'CM' => __( 'Cameroon' ),
        'CA' => __( 'Canada' ),
        'CT' => __( 'Canton and Enderbury Islands' ),
        'CV' => __( 'Cape Verde' ),
        'KY' => __( 'Cayman Islands' ),
        'CF' => __( 'Central African Republic' ),
        'TD' => __( 'Chad' ),
        'CL' => __( 'Chile' ),
        'CN' => __( 'China' ),
        'CX' => __( 'Christmas Island' ),
        'CC' => __( 'Cocos [Keeling] Islands' ),
        'CO' => __( 'Colombia' ),
        'KM' => __( 'Comoros' ),
        'CG' => __( 'Congo - Brazzaville' ),
        'CD' => __( 'Congo - Kinshasa' ),
        'CK' => __( 'Cook Islands' ),
        'CR' => __( 'Costa Rica' ),
        'HR' => __( 'Croatia' ),
        'CU' => __( 'Cuba' ),
        'CY' => __( 'Cyprus' ),
        'CZ' => __( 'Czech Republic' ),
        'CI' => __( 'Côte d’Ivoire' ),
        'DK' => __( 'Denmark' ),
        'DJ' => __( 'Djibouti' ),
        'DM' => __( 'Dominica' ),
        'DO' => __( 'Dominican Republic' ),
        'NQ' => __( 'Dronning Maud Land' ),
        'DD' => __( 'East Germany' ),
        'EC' => __( 'Ecuador' ),
        'EG' => __( 'Egypt' ),
        'SV' => __( 'El Salvador' ),
        'GQ' => __( 'Equatorial Guinea' ),
        'ER' => __( 'Eritrea' ),
        'EE' => __( 'Estonia' ),
        'ET' => __( 'Ethiopia' ),
        'FK' => __( 'Falkland Islands' ),
        'FO' => __( 'Faroe Islands' ),
        'FJ' => __( 'Fiji' ),
        'FI' => __( 'Finland' ),
        'FR' => __( 'France' ),
        'GF' => __( 'French Guiana' ),
        'PF' => __( 'French Polynesia' ),
        'TF' => __( 'French Southern Territories' ),
        'FQ' => __( 'French Southern and Antarctic Territories' ),
        'GA' => __( 'Gabon' ),
        'GM' => __( 'Gambia' ),
        'GE' => __( 'Georgia' ),
        'DE' => __( 'Germany' ),
        'GH' => __( 'Ghana' ),
        'GI' => __( 'Gibraltar' ),
        'GR' => __( 'Greece' ),
        'GL' => __( 'Greenland' ),
        'GD' => __( 'Grenada' ),
        'GP' => __( 'Guadeloupe' ),
        'GU' => __( 'Guam' ),
        'GT' => __( 'Guatemala' ),
        'GG' => __( 'Guernsey' ),
        'GN' => __( 'Guinea' ),
        'GW' => __( 'Guinea-Bissau' ),
        'GY' => __( 'Guyana' ),
        'HT' => __( 'Haiti' ),
        'HM' => __( 'Heard Island and McDonald Islands' ),
        'HN' => __( 'Honduras' ),
        'HK' => __( 'Hong Kong SAR China' ),
        'HU' => __( 'Hungary' ),
        'IS' => __( 'Iceland' ),
        'IN' => __( 'India' ),
        'ID' => __( 'Indonesia' ),
        'IR' => __( 'Iran' ),
        'IQ' => __( 'Iraq' ),
        'IE' => __( 'Ireland' ),
        'IM' => __( 'Isle of Man' ),
        'IL' => __( 'Israel' ),
        'IT' => __( 'Italy' ),
        'JM' => __( 'Jamaica' ),
        'JP' => __( 'Japan' ),
        'JE' => __( 'Jersey' ),
        'JT' => __( 'Johnston Island' ),
        'JO' => __( 'Jordan' ),
        'KZ' => __( 'Kazakhstan' ),
        'KE' => __( 'Kenya' ),
        'KI' => __( 'Kiribati' ),
        'KW' => __( 'Kuwait' ),
        'KG' => __( 'Kyrgyzstan' ),
        'LA' => __( 'Laos' ),
        'LV' => __( 'Latvia' ),
        'LB' => __( 'Lebanon' ),
        'LS' => __( 'Lesotho' ),
        'LR' => __( 'Liberia' ),
        'LY' => __( 'Libya' ),
        'LI' => __( 'Liechtenstein' ),
        'LT' => __( 'Lithuania' ),
        'LU' => __( 'Luxembourg' ),
        'MO' => __( 'Macau SAR China' ),
        'MK' => __( 'Macedonia' ),
        'MG' => __( 'Madagascar' ),
        'MW' => __( 'Malawi' ),
        'MY' => __( 'Malaysia' ),
        'MV' => __( 'Maldives' ),
        'ML' => __( 'Mali' ),
        'MT' => __( 'Malta' ),
        'MH' => __( 'Marshall Islands' ),
        'MQ' => __( 'Martinique' ),
        'MR' => __( 'Mauritania' ),
        'MU' => __( 'Mauritius' ),
        'YT' => __( 'Mayotte' ),
        'FX' => __( 'Metropolitan France' ),
        'MX' => __( 'Mexico' ),
        'FM' => __( 'Micronesia' ),
        'MI' => __( 'Midway Islands' ),
        'MD' => __( 'Moldova' ),
        'MC' => __( 'Monaco' ),
        'MN' => __( 'Mongolia' ),
        'ME' => __( 'Montenegro' ),
        'MS' => __( 'Montserrat' ),
        'MA' => __( 'Morocco' ),
        'MZ' => __( 'Mozambique' ),
        'MM' => __( 'Myanmar [Burma]' ),
        'NA' => __( 'Namibia' ),
        'NR' => __( 'Nauru' ),
        'NP' => __( 'Nepal' ),
        'NL' => __( 'Netherlands' ),
        'AN' => __( 'Netherlands Antilles' ),
        'NT' => __( 'Neutral Zone' ),
        'NC' => __( 'New Caledonia' ),
        'NZ' => __( 'New Zealand' ),
        'NI' => __( 'Nicaragua' ),
        'NE' => __( 'Niger' ),
        'NG' => __( 'Nigeria' ),
        'NU' => __( 'Niue' ),
        'NF' => __( 'Norfolk Island' ),
        'KP' => __( 'North Korea' ),
        'VD' => __( 'North Vietnam' ),
        'MP' => __( 'Northern Mariana Islands' ),
        'NO' => __( 'Norway' ),
        'OM' => __( 'Oman' ),
        'PC' => __( 'Pacific Islands Trust Territory' ),
        'PK' => __( 'Pakistan' ),
        'PW' => __( 'Palau' ),
        'PS' => __( 'Palestinian Territories' ),
        'PA' => __( 'Panama' ),
        'PZ' => __( 'Panama Canal Zone' ),
        'PG' => __( 'Papua New Guinea' ),
        'PY' => __( 'Paraguay' ),
        'YD' => __( "People's Democratic Republic of Yemen" ),
        'PE' => __( 'Peru' ),
        'PH' => __( 'Philippines' ),
        'PN' => __( 'Pitcairn Islands' ),
        'PL' => __( 'Poland' ),
        'PT' => __( 'Portugal' ),
        'PR' => __( 'Puerto Rico' ),
        'QA' => __( 'Qatar' ),
        'RO' => __( 'Romania' ),
        'RU' => __( 'Russia' ),
        'RW' => __( 'Rwanda' ),
        'RE' => __( 'Réunion' ),
        'BL' => __( 'Saint Barthélemy' ),
        'SH' => __( 'Saint Helena' ),
        'KN' => __( 'Saint Kitts and Nevis' ),
        'LC' => __( 'Saint Lucia' ),
        'MF' => __( 'Saint Martin' ),
        'PM' => __( 'Saint Pierre and Miquelon' ),
        'VC' => __( 'Saint Vincent and the Grenadines' ),
        'WS' => __( 'Samoa' ),
        'SM' => __( 'San Marino' ),
        'SA' => __( 'Saudi Arabia' ),
        'SN' => __( 'Senegal' ),
        'RS' => __( 'Serbia' ),
        'CS' => __( 'Serbia and Montenegro' ),
        'SC' => __( 'Seychelles' ),
        'SL' => __( 'Sierra Leone' ),
        'SG' => __( 'Singapore' ),
        'SK' => __( 'Slovakia' ),
        'SI' => __( 'Slovenia' ),
        'SB' => __( 'Solomon Islands' ),
        'SO' => __( 'Somalia' ),
        'ZA' => __( 'South Africa' ),
        'GS' => __( 'South Georgia and the South Sandwich Islands' ),
        'KR' => __( 'South Korea' ),
        'ES' => __( 'Spain' ),
        'LK' => __( 'Sri Lanka' ),
        'SD' => __( 'Sudan' ),
        'SR' => __( 'Suriname' ),
        'SJ' => __( 'Svalbard and Jan Mayen' ),
        'SZ' => __( 'Swaziland' ),
        'SE' => __( 'Sweden' ),
        'CH' => __( 'Switzerland' ),
        'SY' => __( 'Syria' ),
        'ST' => __( 'São Tomé and Príncipe' ),
        'TW' => __( 'Taiwan' ),
        'TJ' => __( 'Tajikistan' ),
        'TZ' => __( 'Tanzania' ),
        'TH' => __( 'Thailand' ),
        'TL' => __( 'Timor-Leste' ),
        'TG' => __( 'Togo' ),
        'TK' => __( 'Tokelau' ),
        'TO' => __( 'Tonga' ),
        'TT' => __( 'Trinidad and Tobago' ),
        'TN' => __( 'Tunisia' ),
        'TR' => __( 'Turkey' ),
        'TM' => __( 'Turkmenistan' ),
        'TC' => __( 'Turks and Caicos Islands' ),
        'TV' => __( 'Tuvalu' ),
        'UM' => __( 'U.S. Minor Outlying Islands' ),
        'PU' => __( 'U.S. Miscellaneous Pacific Islands' ),
        'VI' => __( 'U.S. Virgin Islands' ),
        'UG' => __( 'Uganda' ),
        'UA' => __( 'Ukraine' ),
        'SU' => __( 'Union of Soviet Socialist Republics' ),
        'AE' => __( 'United Arab Emirates' ),
        'GB' => __( 'United Kingdom' ),
        'US' => __( 'United States' ),
        'ZZ' => __( 'Unknown or Invalid Region' ),
        'UY' => __( 'Uruguay' ),
        'UZ' => __( 'Uzbekistan' ),
        'VU' => __( 'Vanuatu' ),
        'VA' => __( 'Vatican City' ),
        'VE' => __( 'Venezuela' ),
        'VN' => __( 'Vietnam' ),
        'WK' => __( 'Wake Island' ),
        'WF' => __( 'Wallis and Futuna' ),
        'EH' => __( 'Western Sahara' ),
        'YE' => __( 'Yemen' ),
        'ZM' => __( 'Zambia' ),
        'ZW' => __( 'Zimbabwe' ),
        'AX' => __( 'Åland Islands' )
    );

    return $data;
}