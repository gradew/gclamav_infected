<?php
/**
 * This plugin displays an icon next to the sender's name
 * if ClamAV identified a virus
 *
 * @version 1.0
 * @author Julien Deriviere
 * @mail contact@gradew.net
 *
 *
 */
class gclamav_infected extends rcube_plugin
{
    public $task = 'mail';
    function init()
    {
        $this->add_hook('storage_init', array($this, 'storage_init'));
        $this->add_hook('message_headers_output', array($this, 'message_headers'));
    }

    function storage_init($p)
    {
        $p['fetch_headers'] = trim($p['fetch_headers'].' ' . strtoupper('X-Virus-Status'));
        return $p;
    }

    function image($image, $alt, $title)
    {
        return '<img src="plugins/gclamav_infected/images/'.$image.'" alt="'.$this->gettext($alt).'" title="'.$this->gettext($alt).htmlentities($title).'" /> ';
    }

    function message_headers($p)
    {
        $this->add_texts('localization');
        if($p['headers']->others['x-virus-status'] ){

            $results = $p['headers']->others['x-virus-status'];
            if(preg_match("/Infected/", $results)) {
                $image = 'radiation.png';
                $alt = 'detected';
                $title=$results;
            }
        }
        if ($image && $alt) {
            $p['output']['from']['value'] = $this->image($image, $alt, $title) . $p['output']['from']['value'];
        }
        return $p;
    }
}
