<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getHtmlCategorys($idEstablecimiento, $selectdItem = null)
{
    $categoryList = '';
    $ci=& get_instance();
    $ci->load->model('category_model','',TRUE);
    $categorys = $ci->category_model->get_categorys($idEstablecimiento);
    foreach($categorys as $category)
    {
        $categoryList .= '<option '; 
        if (isset($selectdItem) && $selectdItem == $category['id']) {
            $categoryList .= 'selected=selected';
        }
        $categoryList .= ' value="' . $category['id'] . '">' . $category['nombre'];
        $categoryList .= '</option>';
    }
    $html = <<<HTML
                <select style="width: 120px;" class="chosen-select" data-placeholder="Sel. Medio" name="category">
                    <option value="">Categorias</option>
                    {$categoryList}
                </select>
HTML;
    return $html;
}

?>