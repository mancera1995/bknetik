<?php

namespace BookneticApp\Providers\Translation;

use BookneticApp\Models\Translation;
use BookneticApp\Providers\Helpers\Helper;

trait Translator
{
    /**
     * Check if the model which uses this trait is translatable
     * @return boolean
     */
    protected static function translatable() {
        if ( isset( self::$translations ) && self::$translations === false ) {
            return false;
        }

        return ! empty( self::getTranslatableAttributes() );
    }

    /**
     * @returns array
     */
    protected static function getTranslatableAttributes() {
        return property_exists( static::class, "translations" ) ? self::$translations : [];
    }

    protected static function isTranslatableAttribute( $attribute ) {
        if ( empty( $attribute ) ) return false;
        return in_array( $attribute, self::getTranslatableAttributes() );
    }

    public static function handleTranslation( $rowId, $data = false ) {

        if ( ! $data )
        {
            $data = Helper::_post( 'translations', '', 'string' );
        }

        $translations = json_decode( $data, TRUE );
        if ( empty( $translations ) || ! is_array( $translations ) || ! self::translatable() ) return;

        foreach ( $translations as $column =>  $translation )
        {
            if ( self::isTranslatableAttribute( $column ) && is_array( $translation ) ) {

                foreach ( $translation as $tItem ) {

                    $id = isset( $tItem[ 'id' ] ) && ! empty( $tItem[ 'id' ] ) ? $tItem[ 'id' ] : 0;
                    $locale = isset( $tItem[ 'locale' ] ) ? $tItem[ 'locale' ] : '';
                    $value  = isset( $tItem[ 'value' ] ) ? $tItem[ 'value' ] : '';

                    if ( empty( $locale ) ) return;

                    if ( $id > 0 ) {

                        Translation::where( 'id', $id )
                            ->update([
                                'locale' => $locale,
                                'value'  => $value
                            ]);

                    } else {

                        Translation::insert([
                            'row_id'      => $rowId,
                            'table_name'  => self::getTableName(),
                            'column_name' => $column,
                            'locale'      => $locale,
                            'value'       => $value
                        ]);

                    }
                }
            }
        }
    }

    public static function translateData( $data ) {
        foreach ( self::getTranslatableAttributes() as $attribute )
        {
            if ( isset( $data[ 'id' ] ) )
            {
                $data->$attribute = self::getTranslatedAttribute( $data[ 'id' ], $attribute, $data->$attribute );
            }
        }

        return $data;
    }

    public static function getTranslatedAttribute( $id, $column, $default ) {
        $translation = Translation::where( [
            'row_id'      => $id,
            'column_name' => $column,
            'table_name'  => self::getTableName(),
            'locale'      => Helper::getLocale()
        ] )->fetch();

        if ( $translation ) {
            return $translation[ 'value' ];
        }

        return $default;
    }
}