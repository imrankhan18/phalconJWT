<?php

namespace App\Locale;

use Phalcon\Di\Injectable;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class Locale extends Injectable
{
    /**
     * @return NativeArray
     */
    public function getTranslator(): NativeArray
    {
        // Ask browser what is the best language
        // $language = $this->request->getBestLanguage();
        $language = $this->request->getQuery('locale');
        $messages = [];
        
        $translationFile = '../app/messages/' . $language . '.php';

        if (true !== file_exists($translationFile)) {
            $translationFile = '../app/messages/en.php';
        }
        
        require $translationFile;

        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);
        // echo "hy";
        // die;
        return $factory->newInstance(
            'array',
            [
                'content' => $messages,
            ]
        );
    }
}