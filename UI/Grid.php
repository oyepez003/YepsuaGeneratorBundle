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

/**
 * Generates a Grid to show the data.
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class Grid extends \YsGrid{
  
  private $translator;
  private $entityName;
  public static $ID_SUFFIX = "Grid";
  public static $RC_PREFIX = "rc";
  private $hasAddButton = true;
  private $hasEditButton = true;
  private $hasDeleteButton = true;
  private $hasShowButton = true;
  private $hasFilterButton = true;
  private $hasSearchButton = true;
  private $hasRefreshButton = true;
  
  public function __construct($entityName, $caption = null, $gridHtmlProperties = null) {
    $this->setSortname(sprintf('%s.id',$entityName));
    $gridId = $entityName . self::$ID_SUFFIX;
    $this->setEntityName(ucfirst($entityName));
    $this->translator = new \Symfony\Component\Translation\Translator('en');
    parent::__construct($gridId, $caption, $gridHtmlProperties);
  }
  
  public function get(){
    $this->configureNavigator();
    $this->configure();
  }
  
  protected function configureNavigator(){
    $navigator = new GridNavigator();
    
    // No Buttons
    $navigator->noDefaultButtons();
    
    if($this->hasAddButton()){
      // Button -> New entity
      $button = new GridCustomButton();
      $button->setCaption('');
      $button->setButtonIcon(UIConstant::ICON_PLUS);
      $button->setTitle($this->translator->trans(sprintf("Add %s", $this->getEntityName())));
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
      $button->setTitle($this->translator->trans(sprintf("Edit %s", $this->getEntityName())));
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
      $button->setTitle($this->translator->trans("Delete selected rows"));
      $button->setOnClickButton(
        new JsFunction(sprintf("%s%s%s()",self::$RC_PREFIX, "Delete", $this->getEntityName()))
      );
      $navigator->addCustomButton($button);
    }
    
    // Button -> Show entity
    $button = new GridCustomButton();
    $button->setCaption('');
    $button->setButtonIcon(UIConstant::ICON_DOCUMENT);
    $button->setTitle($this->translator->trans("Show selected row"));
    $button->setOnClickButton(
      new JsFunction(sprintf("%s%s%s()",self::$RC_PREFIX, "Show", $this->getEntityName()))
    );
    $navigator->addCustomButton($button);
    
    if($this->hasFilterButton()){
      // Filter -> Button
      $button = new GridCustomButton();
      $button->setCaption($this->translator->trans("Filter"));
      $button->setButtonIcon(UIConstant::ICON_PIN_S);
      $button->setTitle($this->translator->trans("Toggle filter"));
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
  public function setTranslator(\Symfony\Component\Translation\Translator $translator) {
    $this->translator = $translator;
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
}