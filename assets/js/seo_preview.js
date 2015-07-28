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

var seoTitle, seoPageTitle, seoDescription, seoPreview, seoCount, seoAlias;

jQuery(document).ready(function($){

  $(document).on('keyup change cut paste','[name^="alias"]',function(){
    updateVars($(this));
    v = $(this).val() ? $(this).val() : seoTitle.val();
    if(seoAlias) {
      re = new RegExp(seoAlias,"g");
      c = seoPreview.find('.url').text().replace( re,v );
      seoPreview.find('.url').empty().append(c);
    }
    seoAlias = v;
  })

  $('[name^="alias"]').change();

  $(document).on('keyup change cut paste','[name^="headline"]',function(){
    updateVars($(this));
    v = $(this).val() ? $(this).val() : seoTitle.val();
    c = v.length + seoPreview.find('.root_page_title').text().length;
    max = seoCount.find('.title .max').text();
    l = max-c;

    if(l < 0) {
      status = 'error';
    } else if(l < 10) {
      status = 'ok';
    } else if(l < 20) {
      status = 'warn';
    } else {
      status = '';
    }

    seoPreview.find('.page_title').empty().append(v);
    seoCount.find('.title .count').empty().append(c);
    seoCount.find('.title').removeClass('error ok warn').addClass(status);
  })

  $('[name^="headline"]').change();

  $(document).on('keyup change cut paste','[name^="meta_description"]',function(){
    updateVars($(this));
    v = $(this).val();
    c = v.length;
    max = seoCount.find('.description .max').text();
    l = max-c;

    if(l < 0) {
      status = 'error';
    } else if(l < 30) {
      status = 'ok';
    } else if(l < 50) {
      status = 'warn';
    } else {
      status = '';
    }

    if(v) {
      seoPreview.find('.description').empty().append(v);
    } else {
      seoPreview.find('.description').empty().append(seoPreview.find('.description').data('empty'));
    }
    seoCount.find('.description .count').empty().append(c);
    seoCount.find('.description').removeClass('error ok warn').addClass(status);
  })

  $('[name^="meta_description"]').change();

  function updateVars(el) {
    //editAll
    if($('[name^="alias_"]').length || $('[name^="headline_"]').length || $('[name^="meta_description_"]').length) {
      p = el.closest('.tl_box, .tl_tbox');
      seoTitle = p.find('[name^="alias"]');
      seoPageTitle = p.find('[name^="headline"]');
      seoDescription = p.find('[name^="meta_description"]');
      seoPreview = p.find('.seo_preview');
      seoCount = p.find('.seo_count');
    } else {
      seoTitle = $('[name^="alias"]');
      seoPageTitle = $('[name^="headline"]');
      seoDescription = $('[name^="meta_description"]');
      seoPreview = $('.seo_preview');
      seoCount = $('.seo_count');
    }
  }

})
