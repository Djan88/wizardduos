<?php

class WAU_User_Accounts_Settings extends WAU_User {
	function __construct( $args ) {

		parent::__construct( $args );
	}

	function init_properties( $args ) {

		$properties = get_class_vars( get_class( $this ) );

		foreach ( $properties as $name => $val ) {
			if ( isset( $args[$name] ) )
				$this->$name = $args[$name];
		}
	}

	function get_settings_html() {

		$content .= '<div class="wau-accounts-list">';

		foreach ( $this->get_only_parents() as $k => $account ) {

			$content .= $this->get_account_html( $account );
		}

		$content .= '<input type="hidden" name="wau-user-settings" value="1">';

		$content .= '</div>';

		return $content;
	}

	function get_account_html( $account ) {

		$content = '<div class="wau-account">';

		$content .= '<input type="checkbox" ' . checked( $this->is_access( $account->account_id ), true, false ) . ' class="account-check" value="' . $account->account_id . '" name="wau-userdata[' . $account->account_id . '][account_id]">';

		$content .= '<span class="account-name">' . apply_filters( 'wau_account_name_user_settings', $account->account_name, $account->account_id, $this->user_id ) . '</span>';

		$content .= '<span class="account-time">';

		$content .= ' Текущее время: '
			. '<input type="text" size="1" value="' . $this->get_current_time_value( 'year', $account->account_id ) . '" name="wau-userdata[' . $account->account_id . '][year]">г. '
			. '<input type="text" size="1" value="' . $this->get_current_time_value( 'month', $account->account_id ) . '" name="wau-userdata[' . $account->account_id . '][month]">м. '
			. '<input type="text" size="1" value="' . $this->get_current_time_value( 'day', $account->account_id ) . '" name="wau-userdata[' . $account->account_id . '][day]">д. '
			. '<input type="text" size="1" value="' . $this->get_current_time_value( 'hour', $account->account_id ) . '" name="wau-userdata[' . $account->account_id . '][hour]">ч. '
			. '<input type="text" size="1" value="' . $this->get_current_time_value( 'minute', $account->account_id ) . '" name="wau-userdata[' . $account->account_id . '][minute]">мин. ';

		$content .= '</span>';

		$content .= $this->get_childrens_html( $account->account_id );

		$content .= '</div>';

		return $content;
	}

	function get_childrens_html( $account_id ) {

		$directChilds = $this->get_direct_childrens( $account_id );

		if ( ! $directChilds )
			return false;

		$content .= '<div class="wau-childrens-list">';

		foreach ( $directChilds as $account ) {
			$content .= $this->get_account_html( $account );
		}

		$content .= '</div>';

		return $content;
	}

}
