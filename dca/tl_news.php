<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   SEOPreviewNews
 * @author    Kirsten Roschanski
 * @author    Valentin Sampl
 * @license   LGPL
 * @copyright 2015
 */

$GLOBALS['TL_DCA']['tl_news']['fields']['seoPreview'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_news']['seoPreview'],
	'input_field_callback' => array('tl_news_seo_preview', 'generatePreview'),
    'eval' => array('tl_class'=>'clr'),
);

/**
 * Add to palettes
 */
$GLOBALS['TL_DCA']['tl_news']['palettes']['default']  = str_replace
(
  'subheadline,',
  'seoPreview, subheadline,',
  $GLOBALS['TL_DCA']['tl_news']['palettes']['default']
);
$GLOBALS['TL_DCA']['tl_news']['palettes']['internal'] = str_replace
(
  'subheadline,',
  'seoPreview, subheadline,',
  $GLOBALS['TL_DCA']['tl_news']['palettes']['internal']
);
$GLOBALS['TL_DCA']['tl_news']['palettes']['external'] = str_replace
(
  'subheadline,',
  'seoPreview, subheadline,',
  $GLOBALS['TL_DCA']['tl_news']['palettes']['external']
);


class tl_news_seo_preview extends Backend {

	public function generatePreview($dc) {

		$GLOBALS['TL_JAVASCRIPT'][] = 'assets/jquery/core/' . $GLOBALS['TL_ASSETS']['JQUERY'] . '/jquery.min.js';
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/seo_preview/assets/js/noconflict.js';
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/seo_preview_news/assets/js/seo_preview.js';

		$GLOBALS['TL_CSS'][] = 'system/modules/seo_preview/assets/css/seo_preview.css';


		$objNews = \Database::getInstance()
							->prepare("SELECT tl_news.alias, tl_news.headline, tl_news.meta_description, tl_news_archive.jumpTo FROM tl_news, tl_news_archive WHERE tl_news.pid = tl_news_archive.id AND tl_news.id=?")
							->limit(1)
							->execute($dc->id);
		$objPage  = $this->getPageDetails($objNews->jumpTo);
		$rootPage = $this->getPageDetails($objPage->trail[0]);

		$objTemplate = new FrontendTemplate('be_seopreview_news');

		$objTemplate->page = $objNews->row();
		$objTemplate->title = $objNews->headline;
		$objTemplate->rootTitle = $rootPage->pageTitle ? $rootPage->pageTitle : $rootPage->title;
		$objTemplate->description = $objNews->meta_description;
		$objTemplate->url = $this->Environment->url.'/'.($GLOBALS['TL_CONFIG']['addLanguageToUrl'] ? $objPage->language.'/' : ''). str_replace( $GLOBALS['TL_CONFIG']['urlSuffix'], '' ,$this->generateFrontendUrl($objPage->row())).'/'.$objNews->alias . $GLOBALS['TL_CONFIG']['urlSuffix'];

		$objTemplate->seo_preview_noDescription = $GLOBALS['TL_LANG']['tl_news']['seo_preview_noDescription'];
		$objTemplate->seo_preview_headline = $GLOBALS['TL_LANG']['tl_news']['seo_preview_headline'];
		$objTemplate->seo_preview_title = $GLOBALS['TL_LANG']['tl_news']['seo_preview_title'];
		$objTemplate->seo_preview_description = $GLOBALS['TL_LANG']['tl_news']['seo_preview_description'];
		$objTemplate->seo_preview_info = $GLOBALS['TL_LANG']['tl_news']['seo_preview_info'];

		return $objTemplate->parse();
	}

}
