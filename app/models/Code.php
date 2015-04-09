<?php

namespace App\Models;

/**
 * Document Class for Mongo-Code Mondel
 */
class Code extends MongoModel {

    /**
     * Class Constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PRIVATE = 'private';

    /**
     * View Link
     * 
     * @var string 
     */
    public static $VIEW_LINK;
    
    
    /**
     * Share Link
     * 
     * @var string 
     */
    public static $SHARE_LINK;

    /**
     * Document required parameters
     * 
     * @var array 
     */
    public static $REQUIRED_PARMS = array(
        'code', 'output', 'version', 'create_time', 'type'
    );

    /**
     * Themes
     * 
     * @var array 
     */
    public static $THEMES = array(
        'Bright Themes' => array(
            "chrome", "crimson_editor", "dawn", "dreamweaver",
            "eclipse", "github", "solarized_light", "textmate",
            "tomorrow", "xcode", "kuroir", "katzenmilch"
        ),
        'Dark Themes' => array(
            "ambiance", "chaos", "clouds_midnight", "cobalt", "idle_fingers",
            "kr_theme", "merbivore", "merbivore_soft", "mono_industrial", "monokai",
            "pastel_on_dark", "solarized_dark", "terminal", "tomorrow_night",
            "tomorrow_night_blue", "tomorrow_night_bright", "tomorrow_night_eighties",
            "twilight", "vibrant_ink"
        )
    );

    /**
     * Returns a preapared Document
     * 
     * @param array $params
     * @return array
     */
    public function getDocument(array $params) {
        return array_merge(parent::getDocument($params), array(
            'expiry' => null,
            'ip' => Utils::getServer('REMOTE_ADDR'),
            'theme' => \Input::get('theme'),
            'status' => self::STATUS_ACTIVE,
            '_id' => new \MongoId()
        ));
    }

    /**
     * Reurns prepared document via static
     * 
     * @return array
     */
    public static function doc() {
        $self = new self();
        return $self->getDocument(self::$REQUIRED_PARMS);
    }

    /**
     * Returns theme from cookie
     */
    public static function cookieSettings() {
        return \Cookie::get('tstgs');
    }

    /**
     * Finds sources by code 
     * 
     * @param type $code
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public static function getCode($code) {
        try {
            $document = Storage::instance('phpsources')
                    ->getCollection()
                    ->findOne(array('_id' => new \MongoId($code)));

            if (! \Session::has('visit-' . $code) && empty(\Input::get('noVisitor'))) {
                \Session::put('visit-' . $code, true);

                Storage::instance('views')
                        ->getCollection()
                        ->update(array('_id' => new \MongoId($document['_id']->{'$id'})), 
                                array('$inc' => array('views' => 1)));
            }
            
            if(empty($document)) {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
            }
            
            $views = Storage::instance('views')
                    ->getCollection()
                    ->findOne(array('_id' => new \MongoId($document['_id']->{'$id'})));

            $document['meta'] = json_encode(array(
                'version' => $document['version'],
                'id' => $document['_id']->{'$id'},
                'create_time' => $document['create_time'],
                'view_link' => self::$SHARE_LINK,
                'views' => $views['views'],
                'theme' => $document['theme'],
                'type' => isset($document['type'])? $document['type']: 'PHP'
            ));

            $document['versions'] = SandBox::versions();

            return $document;
            
        } catch (Exception $e) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }
    }
}
