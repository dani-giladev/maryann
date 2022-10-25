<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\config\controller\config as config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;

/**
 * Medicines info view
 *
 * @author Dani Gilabert
 * 
 */
class medicinesInfo
{    
    
    public $is_individual_article = false;
    public $any_selected_category = false;
    public $any_selected_brand = false;
    
    public function render()
    {
        $current_lang = lang::getCurrentLanguage();
        $html = '';
        
        // Start
        $html .= '<section>';
                
        if ($this->is_individual_article)
        {
            $html .=
                    '<div id="medicines-info-center" class="medicines-info-center-border-top">'.
                    '';
        }
        else
        {
            $html .=
                    '<div id="medicines-info">'.
                         '<div id="medicines-info-center">'.
                    '';
        }
     
        $ecommerce_controller = new ecommerceController();
        $my_pharmacy_is_legal_url = 'https://distafarma.aemps.es/farmacom/faces/sec/CCAA/listadoCCAA.xhtml?farma=7842e51d3aa9d2b0484995d19ef31e4a';
        $sale_of_medicines_url = $ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'sale-of-medicines'));
        //$conditions_of_sale_url = $ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'conditions-of-sale'));
        $conditions_sale_of_medicines_url = $ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'conditions-of-sale#legal-conditions-sale-of-medicines'));
        $health_department_url = 'http://medicaments.gencat.cat/ca/empreses/oficines-de-farmacia/venda-per-internet/';
        $medical_agency_url = 'https://distafarma.aemps.es/farmacom/faces/inicio.xhtml';
        $medical_info_center_url = 'https://www.aemps.gob.es/cima/fichasTecnicas.do?metodo=detalleForm&receta=N&comercializado=S';
        $legal_notice_url = $ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'legal-notice'));
                
        // Authorization to sell medicines?
        $enable_sale_of_medicines = (config::getConfigParam(array("ecommerce", "enable_sale_of_medicines"))->value);
        $html .= 
                '<table id="medicines-info-table" border="0">'.
                    '<tr>'.
                        '<td>'.
                
                            // Suggested pharmacy logo by distafarma
//                            '<a href="https://distafarma.aemps.es/farmacom/faces/sec/CCAA/listadoCCAA.xhtml?farma=7842e51d3aa9d2b0484995d19ef31e4a" target="_blank">'.
//                                '<img src="https://distafarma.aemps.es/farmacom/ServletIcono?farma=7842e51d3aa9d2b0484995d19ef31e4a" height="60px;"/>'.
//                            '</a>'.  
//                            
                            // My pharmacy logo
                            '<a href="'.$my_pharmacy_is_legal_url.'" target="_blank">'.
                                '<img id="medicines-info-logo" '.
                                    'src="'.
                                        "/modules/ecommerce/frontend/res/img/logos/ServletIcono".($enable_sale_of_medicines? '' : '-no-authorized-yet').'.jpg'.
                                        //'https://distafarma.aemps.es/farmacom/ServletIcono?farma=7842e51d3aa9d2b0484995d19ef31e4a'.
                                    '"'.
                                '/>'.
                            '</a>'.
                

                        '</td>'.
                        '<td id="medicines-info-column-text">'.
                '';
        
        if ($this->is_individual_article)
        {
            $html .= 
                            '<b>Este artículo es un medicamento</b> de uso humano no sujeto a prescripción médica.'.' '.
                            'Únicamente las farmacias online autorizadas por la Agencia Española del Medicamento, tienen autorización para vender este tipo de artículos'.
                    '';
        }
        else
        {
            if ($this->any_selected_category)
            {
                $html .=    'Los artículos de esta categoría';
            }
            elseif ($this->any_selected_brand)
            {
                $html .=    'Los artículos de esta marca';
            }
            else
            {
                $html .=    'Los artículos de esta categoría o marca';
            }
            $html .= 
                            ', '.
                            'son medicamentos de uso humano no sujetos a prescripción médica'.
                    '';
        }
        
        $html .= 
                            '. '.
                            'Puede consultar las condiciones de venta de medicamentos haciendo clic'.' '.
                                '<a href="'.$sale_of_medicines_url.'" class="medicines-info-link">'.
                                    'aquí'.
                                '</a>'.
                            ' '.'o consultando'.' '.
                                '<a href="'.$conditions_sale_of_medicines_url.'" class="medicines-info-link">'.
                                    'el punto 11 de'.' '.lang::trans('conditions_of_sale').
                                '</a>'.
                            '.</br></br>'.
                
                            'Puede obtener más información sobre los medicamentos y verificar las farmacias autorizadas para vender medicamentos de uso humano no sujetos a prescripción médica, en los siguientes enlaces:'.
                            '</br>'.
                            '<ul>'.
                                '<li>'.
                                    '<a href="'.$health_department_url.'" class="medicines-info-link2" target="_blank">'.
                                        'Departamento de Salud'.
                                    '</a>'.
                                '</li>'.
                                '<li>'.
                                    '<a href="'.$medical_agency_url.'" class="medicines-info-link2" target="_blank">'.
                                        'Agencia Española del Medicamento y Productos Sanitarios'.
                                    '</a>'.
                                '</li>'.
                                '<li>'.
                                    '<a href="'.$medical_info_center_url.'" class="medicines-info-link2" target="_blank">'.
                                        'Centro de información de medicamentos CIMA, de la Agencia Española del Medicamento y Productos Sanitarios'.
                                    '</a>'.
                                '</li>'.
                            '</ul>'.
                
                            'Puede obtener más información sobre la farmacia y los farmacéuticos que le atenderan durante el proceso de venta de medicamentos en'.
                            ' '.
                                '<a href="'.$legal_notice_url.'" class="medicines-info-link">'.
                                    lang::trans('legal_notice').
                                '</a>'.
                            '.'.
                
                        '</td>'.
                    '</tr>'.
                '</table>'.
                '';
        
        // End
        if ($this->is_individual_article)
        {
            $html .= 
                    '</div>'.
                ''; 
        }
        else
        {
            $html .= 
                        '</div>'.
                    '</div>'.
                '';
        }        
        $html .= '</section>';        
        
        return $html;
    }
    

}