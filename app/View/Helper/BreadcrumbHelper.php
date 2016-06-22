<?php
App::uses('AppHelper', 'View/Helper');

class BreadcrumbHelper extends AppHelper 
{
	public $helpers = array('Html','Js' => array('Jquery'));
	/*
	$links array containing information to create links
	'title' => 'Text shown for link'
	'url' => url to redirect, can be created by sending the controller and action
	*/
	public function create($links)
	{
		$html = '<ol class="breadcrumb">';
		$html .= '<li>'.$this->Js->link($links[0]['title'],$links[0]['url'],
				array(
					'before' => $this->Js->get('#loading')->effect('fadeIn'), 
					// #loading - id to show the gif while content loads
					'success' => $this->Js->get('#loading')->effect('fadeOut'),
					'update' => '#content'
				)
			);
		$html .= '</li>';
		//removing the home index and displaying the rest
		array_shift($links);
		foreach($links as $key => $link)
		{
			$html .= '<li>';
			// separtor for links, you can also use any symbol, its your application, you are the boss
			$options_array = array(
					'before' => $this->Js->get('#loading')->effect('fadeIn'),
					'success' => $this->Js->get('#loading')->effect('fadeOut'),
					'update' => '#content'
					);
			++$key;

			if(count($links) == $key){
				$options_array['class'] = 'active';
			}

			$html .= $this->Js->link($link['title'],$link['url'], $options_array);
			
			$html .= '</li>';
		}
		$html .= '</ol>';
		return $html;
	}
}