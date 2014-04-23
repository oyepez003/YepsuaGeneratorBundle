<?php

/*
 * This file is part of the YepsuaGeneratorBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\GeneratorBundle\UI;

use \YsGridForm as GridForm;
use \YsUIConstant as UIConstant;
use \YsJsFunction as JsFunction;
use \YsGridNavigator as GridNavigator;
use \YsGridConstants as GridConstants;
use \YsGridCustomButton as GridCustomButton;
use \YsGridFilterToolbar as GridFilterToolbar;
use \YsGridField as GridField;
use \YsJQueryBuilder as JQueryBuilder;
use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;

use \Yepsua\CommonsBundle\IO\ObjectUtil;

JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);

/**
 * Generates a Grid to show the data.
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class Grid extends \YsGrid{
  
  private $translator;
  private $entityName;
  public static $ID_SUFFIX = "Grid";
  public static $RC_PREFIX = "rc";
  public static $CRUD_TRANSLATION_DOMAIN = "richcrud";
  public static $TRANSLATION_DOMAIN = "messages";
  private $hasAddButton = true;
  private $hasEditButton = true;
  private $hasDeleteButton = true;
  private $hasShowButton = true;
  private $hasFilterButton = true;
  private $hasSearchButton = true;
  private $hasRefreshButton = true;
  private $translatorDomain;
  private $translatorProperties;
  private $translatorLocale;
  private $entityManager;
  
  public function __construct($entityName, $caption = null, $gridHtmlProperties = null) {
    $this->setMultiboxOnly(true);
    $this->setSortname(sprintf('%s.id',$entityName));
    $gridId = $entityName . self::$ID_SUFFIX;
    $this->setEntityName(ucfirst($entityName));
    $this->setTranslatorProperties(array());
    parent::__construct($gridId, $caption, $gridHtmlProperties);
    $this->hideFilterToolbar();
  }
  
  public function hideFilterToolbar(){
      $this->addPostSintax(sprintf("%s('.ui-search-toolbar').hide()",  JQueryBuilder::$jqueryVar));
  }
  
  /**
   * @deprecated
   */
  public function get($withCRUDButton = false){
    $this->createView($withCRUDButton);
  }
  
  public function createView($withCRUDButton = false){
    $this->_getNavigator();
    if($withCRUDButton){
        $this->addCRUDButtons();
    }
    $this->configureNavigator();
    $this->configure();
  }
  
  public function _getNavigator(){
    return ($this->getNavigator() !== null) 
           ? $this->getNavigator() 
           : new GridNavigator();
  }
  
  public function addCRUDButtons(){
    $navigator = $this->_getNavigator();
    if($this->hasAddButton()){
      // Button -> New entity
      $button = new GridCustomButton();
      $button->setCaption('');
      $button->setButtonIcon(UIConstant::ICON_PLUS);
      $button->setTitle($this->translator->trans('grid.btn.new.title'));
      $button->setOnClickButton(
        new JsFunction(sprintf("%s%s%s()",self::$RC_PREFIX, "New", $this->getEntityName()))
      );
      $navigator->addCustomButton($button);
    }
    
    if($this->hasEditButton()){
      // Button -> Edit entity
      $button = new GridCustomButton();
      $button->setCaption('');
      $button->setButtonIcon(UIConstant::ICON_PENCIL);
      $button->setTitle($this->translator->trans('grid.btn.edit.title'));
      $button->setOnClickButton(
        new JsFunction(sprintf("%s%s%s()",self::$RC_PREFIX, "Edit", $this->getEntityName()))
      );
      $navigator->addCustomButton($button);
    }

    if($this->hasDeleteButton()){
      // Button -> Delete entity
      $button = new GridCustomButton();
      $button->setCaption('');
      $button->setButtonIcon(UIConstant::ICON_CLOSE);
      $button->setTitle($this->translator->trans("grid.btn.delete.title"));
      $button->setOnClickButton(
        new JsFunction(sprintf("%s%s%s()",self::$RC_PREFIX, "Delete", $this->getEntityName()))
      );
      $navigator->addCustomButton($button);
    }
    
    // Button -> Show entity
    $button = new GridCustomButton();
    $button->setCaption('');
    $button->setButtonIcon(UIConstant::ICON_DOCUMENT);
    $button->setTitle($this->translator->trans("grid.btn.show.title"));
    $button->setOnClickButton(
      new JsFunction(sprintf("%s%s%s()",self::$RC_PREFIX, "Show", $this->getEntityName()))
    );
    $navigator->addCustomButton($button);
    $this->setNavigator($navigator);
  }
  
  protected function configureNavigator(){
    $navigator = $this->_getNavigator();
    
    // No Buttons
    $navigator->noDefaultButtons();
    
    
    
    if($this->hasFilterButton()){
      // Filter -> Button
      $button = new GridCustomButton();
      $button->setCaption($this->translator->trans("grid.btn.filter"));
      $button->setButtonIcon(UIConstant::ICON_PIN_S);
      $button->setTitle($this->translator->trans("grid.btn.filter.title"));
      $button->setOnClickButton(
        new JsFunction(sprintf("$('#%s')[0].toggleToolbar()",$this->getGridId()))
      );
      $navigator->addCustomButton($button);
    }
    
    
    if($this->hasSearchButton()){
      // Search -> Button
      $searchForm = new GridForm();
      $searchForm->setMultipleSearch(true);
      $navigator->setSearchForm($searchForm);
    }
    if($this->hasRefreshButton()){
      // Refresh -> Button
      $navigator->setRefresh(true);
      $this->setNavigator($navigator);
    }
  }
  
  public function configure(){
    $this->setWidth("100%");
    $this->setHeight('auto');
    $this->setDataType(GridConstants::DATA_TYPE_JSON);
    $this->setRowNum(5);
    $this->setRowList(array(3,5,10));
    $this->setViewRecords(true);
    
    $filterToolBar = new GridFilterToolbar();
    $filterToolBar->setStringResult(true);
    $filterToolBar->setSearchOnEnter(true);
    $filterToolBar->setToggleToolbar(true);
    $this->setFilterToolbar($filterToolBar);
    
    $this->setMultiselect(true);
    $this->setRowNumbers(true);
  }
  
  /**
   * The Symfony2 tranlator
   * @return \Symfony\Component\Translation\Translator 
   */
  public function getTranslator() {
    return $this->translator;
  }
  
  /**
   * The Symfony2 tranlator
   * @param \Symfony\Component\Translation\Translator $translator 
   */
  public function setTranslator($translator, $translatorDomain = null, $translatorLocale = null) {
    $this->translator = $translator;
    $this->setTranslatorDomain($translatorDomain);
    $this->setTranslatorLocale($translatorLocale);
    $this->setCaption($this->translate($this->getCaption()));
  }
  
  private function translate($value){
    return $this->getTranslator()->trans($value, $this->getTranslatorProperties() , $this->getTranslatorDomain());
  }
  
  public function getEntityName() {
    return $this->entityName;
  }

  public function setEntityName($entityName) {
    $this->entityName = $entityName;
  }
    
  public function hasAddButton() {
    return $this->hasAddButton;
  }

  public function setHasAddButton($hasAddButton) {
    $this->hasAddButton = $hasAddButton;
  }

  public function hasEditButton() {
    return $this->hasEditButton;
  }

  public function setHasEditButton($hasEditButton) {
    $this->hasEditButton = $hasEditButton;
  }

  public function hasDeleteButton() {
    return $this->hasDeleteButton;
  }

  public function setHasDeleteButton($hasDeleteButton) {
    $this->hasDeleteButton = $hasDeleteButton;
  }

  public function hasShowButton() {
    return $this->hasShowButton;
  }

  public function setHasShowButton($hasShowButton) {
    $this->hasShowButton = $hasShowButton;
  }

  public function hasFilterButton() {
    return $this->hasFilterButton;
  }

  public function setHasFilterButton($hasFilterButton) {
    $this->hasFilterButton = $hasFilterButton;
  }
  
  public function hasSearchButton() {
    return $this->hasSearchButton;
  }

  public function setHasSearchButton($hasSearchButton) {
    $this->hasSearchButton = $hasSearchButton;
  }
  
  public function hasRefreshButton() {
    return $this->hasRefreshButton;
  }

  public function setHasRefreshButton($hasRefreshButton) {
    $this->hasRefreshButton = $hasRefreshButton;
  }
  
  public function noWriteActions(){
    $this->setHasDeleteButton(false);
    $this->setHasEditButton(false);
    $this->setHasAddButton(false);
  }
  
  public function addGridField(GridField $gridFields) {
    $gridFields->setColName($this->translate($gridFields->getColName()));
    parent::addGridField($gridFields);
  }
  
  public function setArrayGridField($gridFields) {;
    if(is_array($gridFields)){
      foreach ($gridFields as $key => $value) {
        $field = new GridField($key, null);
        if(is_array($value)){
          if( isset($value['title']) ){
            $field->setColName($value['title']);
          }
          foreach ($value as $_key => $_value) {
            $field[$_key] = $_value;
          }
        }else{
          $field->setColName($value);
        }
        if(isset($value['association'])){
          $searchPattern = isset($value['searchPattern']) ? $value['searchPattern'] : ";%KEY%:%VALUE%";
          $searchType = isset($value['searchType']) ? $value['searchType'] : GridConstants::EDIT_TYPE_SELECT;
          $field->setSearchOptions(array(
            'value' => ':' . ObjectUtil::entityToKeyValue(
              $this->getEntityManager()->getRepository($value['association'])->findAll(), $searchPattern
            )
          ));
          $field->setSType($searchType);
          
        }
        $this->addGridField($field);
      }
    }
  }
  
  public function getTranslatorDomain() {
    return $this->translatorDomain;
  }

  public function setTranslatorDomain($translatorDomain) {
    if($translatorDomain !== null){
      $this->translatorDomain = $translatorDomain;
    }
  }

    
  public function getTranslatorProperties() {
    return $this->translatorProperties;
  }

  public function setTranslatorProperties($translatorProperties) {
    $this->translatorProperties = $translatorProperties;
  }

  public function getTranslatorLocale() {
    return $this->translatorLocale;
  }

  public function setTranslatorLocale($translatorLocale) {
    if($translatorLocale !== null){
      $this->translatorLocale = $translatorLocale;
    }
  }
  
  public function getEntityManager() {
    return $this->entityManager;
  }

  public function setEntityManager($entityManager) {
    $this->entityManager = $entityManager;
  }
  
  public function renewNavigator(){
      $this->setNavigator(new \YsGridNavigator());
  } 
}