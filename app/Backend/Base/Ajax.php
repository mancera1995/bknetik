<?php

namespace BookneticApp\Backend\Base;

use BookneticApp\Backend\Settings\Helpers\LocalizationService;
use BookneticApp\Models\Location;
use BookneticApp\Models\Service;
use BookneticApp\Models\Staff;
use BookneticApp\Models\Translation;
use BookneticApp\Providers\Helpers\Helper;
use BookneticApp\Providers\Helpers\Session;

class Ajax extends \BookneticApp\Providers\Core\Controller
{

	public function switch_language()
	{
		if( !Helper::isSaaSVersion() )
		{
			return $this->response( false );
		}

		$language = Helper::_post('language', '', 'string');

		if( LocalizationService::isLngCorrect( $language ) )
		{
			Session::set('active_language', $language);
		}

		return $this->response( true );
	}


	public function ping()
	{
		return $this->response( true );
	}

    public function direct_link()
    {
        $service_id     = Helper::_post('service_id' ,0 ,'int');
        $staff_id       = Helper::_post('staff_id' ,0 ,'int');
        $location_id    = Helper::_post('location_id' ,0 ,'int');
        $services   = Service::fetchAll();
        $staff      = Staff::fetchAll();
        $locations  = Location::fetchAll();

        return $this->modalView('direct_link' , compact('services' , 'staff' , 'locations' ,'service_id' ,'staff_id' ,'location_id'));
    }

    public function get_translations() {
        $rowId        = Helper::_post( 'row_id', '0', 'int' );
        $tableName    = Helper::_post( 'table', '', 'string' );
        $columnName   = Helper::_post( 'column', '', 'string' );
        $translations = json_decode( Helper::_post( 'translations', '', 'string' ), TRUE );
        $nodeType     = Helper::_post( 'node', 'input', 'string', [ 'input', 'textarea' ] );

        if ( empty( $tableName ) || empty( $columnName ) )
        {
            return $this->response( false, [
                'message' => 'Fields are not correct',
            ] );
        }

        // translationlari elave edib, sonra modali baglayib yeniden translation modalini acanda inputun translation datasini gonderirikki db da saxlanilmayan translationlari gore bilek
        if ( ! empty( $translations ) && is_array( $translations ) )
        {
            return $this->modalView( 'translations', [
                'translations' => $translations,
                'node'         => $nodeType,
                'id'           => $rowId,
                'column'       => $columnName ,
                'table'        => $tableName
            ] );
        }

        if ( $tableName === 'options' )
        {
            $translations = Translation::where( [
                'table_name'  => $tableName,
                'column_name' => $columnName
            ] )->fetchAll();
        }
        else if ( $rowId > 0 )
        {
            $translations = Translation::where( [
                'row_id'      => $rowId,
                'table_name'  => $tableName,
                'column_name' => $columnName
            ] )->fetchAll();
        } else
        {
            $translations = [];
        }

        return $this->modalView( "translations", [
            'translations' => $translations,
            'node'         => $nodeType,
            'id'           => $rowId,
            'column'       => $columnName,
            'table'        => $tableName
        ] );
    }

    public function save_translations() {
        $translations = json_decode( Helper::_post( 'translations', '', 'string' ), TRUE );
        $tableName    = Helper::_post( 'table_name', '', 'string', [ 'services', 'staff', 'service_categories' ,'locations', 'service_extras', 'form_inputs', 'form_input_choices', 'taxes', 'options' ] );
        $columnName   = Helper::_post( 'column_name', '', 'string' );
        $rowID        = Helper::_post( 'row_id', 0, 'int' );

        if ( empty( $translations ) || ! is_array( $translations ) || empty( $tableName ) || empty( $columnName ) )
        {
            return $this->response( false, [
                'message' => 'Please fill in all required fields correctly',
            ] );
        }

        foreach ( $translations AS $translation )
        {
            $id  = isset( $translation[ 'id' ] ) && ! empty( $translation[ 'id' ] ) ? $translation [ 'id' ] : 0;
            $locale = isset( $translation[ 'locale' ] ) && ! empty( $translation[ 'locale' ] ) ? $translation[ 'locale' ] : '';
            $value  = isset( $translation[ 'value' ] ) ? $translation[ 'value' ] : '';

            if ( empty( $locale ) ) continue;

            if ( $id > 0 )
            {
                Translation::where( [
                    'id' => $id,
                ] )->update( [
                    'locale' => $locale,
                    'value'  => $value
                ] );
            } else
            {
                Translation::insert( [
                    'row_id'       => $rowID,
                    'column_name'  => $columnName,
                    'table_name'   => $tableName,
                    'locale'       => $locale,
                    'value'        => $value
                ] );
            }
        }

        return $this->response( 200, [
            'message' => 'Saved successfully'
        ] );
    }

    public function delete_translation() {
        $id = Helper::_post( 'id', 0, 'int' );

        if ( empty( $id ) )
        {
            return $this->response( false );
        }

        Translation::where( 'id', $id )->delete();

        return $this->response( true );
    }

}
