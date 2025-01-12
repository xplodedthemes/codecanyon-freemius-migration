<?php
/**
 * CodeCanyon to Freemius API Class
 *
 * @author      Arindo Duque
 * @category    Admin
 * @package     WP_Ultimo/Model
 * @version     0.0.1
 */

if (!defined('ABSPATH')) {
	exit;
} // end if;


/**
 * CTF_Api class.
 */
class CTF_Api {

	public $freemius_dev_pk_apikey;
	public $freemius_dev_sk_apikey;
	public $freemius_dev_id;

	public $freemius_plugin_pk_apikey;
	public $freemius_plugin_sk_apikey;
	public $freemius_plugin_id;
	public $freemius_plugin_plan_id;
	public $freemius_plugin_pricing_id;
	public $freemius_plugin_expires_grace_period;

	public $codecanyon_api_key;

	/**
	 * Constructor
	 *
	 * @since 0.0.1
	 *
	 * @param array $data Data of api.
	 */
	public function __construct($data) {

		$this->freemius_dev_pk_apikey = $data['freemius_dev_pk_apikey'];
		$this->freemius_dev_sk_apikey = $data['freemius_dev_sk_apikey'];
		$this->freemius_dev_id        = $data['freemius_dev_id'];

		$this->freemius_plugin_pk_apikey  = $data['freemius_plugin_pk_apikey'];
		$this->freemius_plugin_sk_apikey  = $data['freemius_plugin_sk_apikey'];
		$this->freemius_plugin_id         = $data['freemius_plugin_id'];
		$this->freemius_plugin_plan_id    = $data['freemius_plugin_plan_id'];
		$this->freemius_plugin_pricing_id = $data['freemius_plugin_pricing_id'];
		$this->freemius_plugin_expires_grace_period = $data['freemius_plugin_expires_grace_period'];

		$this->codecanyon_api_key     = $data['codecanyon_api_key'];

	}  // end __construct;

	/**
	 * Create new user in Freemius.
	 *
	 * @since 0.0.1
	 *
	 * @param string $email User email.
	 *
	 * @return array
	 */
	public function create_freemius_user($email) {

		$api = new CTF_Freemius_Api('developer', $this->freemius_dev_id, $this->freemius_dev_pk_apikey, $this->freemius_dev_sk_apikey);

		try {
			// {plan_id: "4675", pricing_id: "3841", expires_at: "2099-08-22 03:00:00", send_email: true, email: "marcelo@wpultimo.com", period: 12}
			$result = $api->Api('/plugins/plugin_id/users.json', 'POST', array(
				'email'                   => $email,
				'password'                => uniqid(rand()),
				'name'                    => explode('@', $email)[0],
				'plugin_id'               => $this->freemius_plugin_id,
				'send_verification_email' => false,
				'is_verified'             => true,

			));

		} catch (Exception $e) {
			return false;
		} // end try;

		return $result;

	} // end create_freemius_user;

	/**
	 * Create new license key in freemius.
	 *
	 * @since 0.0.1
	 *
	 * @param string $email User email.
     * @param string $envato_license Envato License

	 * @return array
	 */
	public function create_freemius_license($email, $envato_license = null) {

	    if(empty($envato_license) || empty($envato_license->supported_until)) {
	        return false;
        }

		$api = new CTF_Freemius_Api('developer', $this->freemius_dev_id, $this->freemius_dev_pk_apikey, $this->freemius_dev_sk_apikey);

        $expiration_date = strtotime($envato_license->supported_until);
        $today = strtotime("today midnight");
        $expired = $expiration_date < $today;

        if($expired){
            $expire_at = date('Y-m-d H:i:s', strtotime('+'.$this->freemius_plugin_expires_grace_period));
        } else {
            $expire_at = date('Y-m-d H:i:s', strtotime('+'.$this->freemius_plugin_expires_grace_period, $expiration_date));
        }

		try {
			// {plan_id: "4675", pricing_id: "3841", expires_at: "2099-08-22 03:00:00", send_email: true, email: "marcelo@wpultimo.com", period: 12}
			$result = $api->Api('/plugins/'.$this->freemius_plugin_id.'/plans/'.$this->freemius_plugin_plan_id.'/pricing/'.$this->freemius_plugin_pricing_id.'/licenses.json', 'POST', array(
				'email'             => $email,
				'plan_id'           => $this->freemius_plugin_plan_id,
				'pricing_id'        => $this->freemius_plugin_pricing_id,
				'plugin_id'         => $this->freemius_plugin_id,
				'expires_at'        => $expire_at,
				'send_email'        => true,
				'is_block_features' => false,
				'source'            => 6
			));

		} catch (Exception $e) {
			return false;
		} // end try;

        $api->Api('/plugins/'.$this->freemius_plugin_id.'/licenses/'.$result->id.'.json', 'PUT', array(
            'is_block_features' => false
        ));

		return $result;

	}  // end create_freemius_license;

	/**
	 * Check if has existing user using this $email in Freemius.
	 *
	 * @since 0.0.1
	 *
	 * @param string $email User email.
	 *
	 * @return boolean||array
	 */
	public function verify_freemius_exists_user($email) {

		$api = new CTF_Freemius_Api('plugin', $this->freemius_plugin_id, $this->freemius_plugin_pk_apikey, $this->freemius_plugin_sk_apikey);

		try {

			$result = $api->Api('/users.json?' . http_build_query(array('search' => $email)));

		} catch (Exception $e) {
			return false;
		} // end try;

		return empty($result->users) ? false : $result->users[0];

	}  // end verify_freemius_exists_user;

	/**
	 * Check if user has existing one license actived on specific plugin.
	 *
	 * @since 0.0.1
	 *
	 * @param int $user_id User id.
	 *
	 * @return null||array
	 */
	public function get_licences_by_user_id($user_id) {

		$api = new CTF_Freemius_Api('plugin', $this->freemius_plugin_id, $this->freemius_plugin_pk_apikey, $this->freemius_plugin_sk_apikey);

		try {

			$result = $api->Api('users/' . $user_id . '/licenses.json?' . http_build_query(array('plugin_id' => $this->freemius_plugin_id, 'source' => 6)));

		} catch (Exception $e) {
			return false;
		} // end try;

		foreach ($result->licenses as $license) {

			if ($license->plugin_id == $this->freemius_plugin_id && !$license->is_cancelled) {

				return $license;

			} // end if;

		} // end foreach;

		return null;

	} // end get_licences_by_user_id;

	/**
	 * Check if CodeCanyon license key exists
	 *
	 * @since 0.0.1
	 *
	 * @param array $code CodeCanyon License key.
	 *
	 * @return object
	 */
	public function verify_envato_purchase_code($code, $codecanyon_id) {

		if (strlen($code) !== 36) {
			return false;
		} // end if;

		$response = wp_remote_get('https://api.envato.com/v3/market/author/sale?code=' . $code, array(
			'timeout' => 300,
			'headers' => array('Authorization' => 'Bearer ' . $this->codecanyon_api_key),
		));

		if (is_wp_error($response)) {

			return (object) array(
				'success'       => false,
                'supported_until' => null,
				'golden_ticket' => false,
				'purchase'      => (object) array(
					'refunded' => false,
				)
			);

		} // end if;

		// Decode returned JSON
		$output = json_decode(wp_remote_retrieve_body($response));

		// Invalid license

		if ( !isset($output->buyer) || ((string)$output->item->id !== (string)$codecanyon_id)) {
			return (object) array(
				'success'       => false,
				'supported_until' => $output->supported_until,
				'golden_ticket' => isset($output->buyer),
				'purchase'      => (object) array(
					'refunded' => false,
				)
			);
		} // end if;

		return (object) array(
			'success'       => isset($output->buyer),
            'supported_until' => $output->supported_until,
			'golden_ticket' => isset($output->buyer),
			'purchase'      => (object) array(
				'refunded' => false,
			)
		);

	} // end verify_envato_purchase_code;

}   // end class CTF_Api;
