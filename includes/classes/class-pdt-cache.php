<?php

class PDT_Cache {
    /**
	 * The instance of this class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      RFM_Cache    $instance    The instance of this class.
	 */
	private static $instance;
	
	/**
	 * Return an instance of RFM_Cache
	 * @since 1.0.0
	 * @access public
	 */ 
	public static function get_instance() {
	    
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
        }
        return self::$instance;	
	}
	
	/***
	 *
	 * @since 1.0.0
	 * @param   $key     string|integer|bool    The key of Cache
	 * @param   $key     string|integer|float   The Content of Cache
	 * @param   $expired integer                The time of caching 
	 */
	public function set_cache( $key, $data, $expired = 300 ) {
	    
	    set_transient( $key, $data, $expired );
	    
	}
	
	/***
	 *
	 * @since 1.0.0
	 * @param   $key     string|integer|bool    The key of Cache
	 * @return  string|integer|float   The Content of Cache
	 * 
	 */
	public function get_cache( $key ) {
	    
	    return get_transient( $key );
	    
	}
	
	
	/***
	 *
	 * @since 1.0.0
	 * @param   $key     string|integer|bool    The key of Cache
	 * 
	 */
	public function delete_cache( $key ) {
	    
	    delete_transient( $key );
	    
	}
	public function clear_rfm_cache( $args ) {
	    
	    if ( in_array( "chart_query", $args ) ) {
	        $this->clear_chart_cache();
	        $this->clear_chart_data();
	    }
	    
	    if ( in_array( "time_period_setting", $args ) ) {
	        $this->clear_time_period_cache();
	    }

        if ( in_array( "basic_analaysis_setting", $args ) ) {
	        $this->clear_basic_analaysis_setting_cache();
	    }

	    if ( in_array( "rfm_setting", $args ) ) {
	    	$this->clear_rfm_settings_data();
	    }
	}
	
	public function clear_chart_data() {
	    delete_transient( 'rfm_frequency_chart_data' );
	    delete_transient( 'rfm_monetary_chart_data' );
	    delete_transient( 'rfm_recency_chart_data' );
	}
	
	public function clear_chart_cache() {
	    delete_transient( 'rfm_frequency_chart_query' );
	    delete_transient( 'rfm_monetary_chart_query' );
	    delete_transient( 'rfm_recency_chart_query' );
	}
	
	public function clear_time_period_cache() {
	    delete_transient( 'rfm_time_period' );
	}
	
	public function clear_basic_analaysis_setting_cache() {
	    delete_transient( 'rfm_basic_analysis_row' );
	}

	public function clear_rfm_settings_data() {
	    delete_transient( 'rfm_settings_data' );
	}
}