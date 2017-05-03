<?php

namespace App\Repositories\Admin;

use App\Repositories\Admin\UtilityRepository;
use App\Components\Plugin;
use App\Models\FieldSections;
use App\Models\Fields;
use App\Models\FieldOptions;
use App\Models\UserFields;

class ProfileManageRepository {

    public function __construct(Fields $fields, FieldSections $fieldSections, FieldOptions $fieldOptions, UserFields $userFields){
        $this->fields        = $fields;
        $this->fieldSections = $fieldSections;
        $this->fieldOptions  = $fieldOptions;
        $this->userFields    = $userFields;
    }



    public function edit_field($arr)
    {
        $field = $this->fields->where('id',$arr['id'])->first();

        if(isset($arr['register']))
            $field->on_registration = $arr['register'];
        elseif(isset($arr['search']))
            $field->on_search = $arr['search'];
            
        $field->save();
        return $field;
    }

    public function post_add_section($name)
    {
        $section = clone $this->fieldSections;
        $section->name = $name;
        $section->code = $this->make_code($name);
        $section->save();
        $this->addInLangFile($section->code,$section->name);
        return $section;
    }

    public function post_add_field ($arr) {

        $field = clone $this->fields;

        $field->name            = $arr['new_field'];
        $field->code            = $this->make_code($arr['new_field']);
        $field->section_id      = $arr['section_id'];
        $field->type            = $arr['type'];
        $field->on_registration = $arr['register'];
        $field->on_search       = $arr['search'];
        $field->on_search_type  = $arr['search_type'];
        $field->unit            = $arr['unit'];

        $field->save();

        $this->addInLangFile($field->code,$field->name);
        return $field;
    
    }

    
    public function post_add_field_option ($arr) {

        $option = clone $this->fieldOptions;

        $code             = (isset($arr['unit'])) ? $arr['optiontitle'].$arr['unit'] : $arr['optiontitle'];
        $option->name     = $arr['optiontitle'];
        $option->field_id = $arr["field"];
        $option->code     = $this->make_code($code);
        $option->save();

        $trans_text = (isset($arr['unit'])) 
                        ? $option->name. ' '. $arr['unit']
                        : $option->name;
       
        $this->addInLangFile($option->code,$trans_text);
        return $option;
        
    }

    public function delete_section($id)
    {
        $section = $this->fieldSections->where('id','=',$id)->first();
        foreach($section->fields as $field)
        {
            foreach($field->field_options as $option)
            {
                $option->delete();
            }
            Plugin::fire('custom_section_field_deleted', $field);

            $this->userFields->where('field_id', $field->id)->forceDelete();
            $field->delete();            
        }
        $section->delete();
    }

    public function delete_field($id)
    {
        $field = $this->fields->where('id','=',$id)->first();
        foreach($field->field_options as $option)
        {
            $option->delete();
        }
        Plugin::fire('custom_section_field_deleted', $field);
        $this->userFields->where('field_id', $field->id)->forceDelete();
        $field->delete();
    }

    public function delete_option($id)
    {
        $option = $this->fieldOptions->where('id','=',$id)->first();
        $this->userFields->where('value', $option->id)->forceDelete();
        $option->delete();
    }

    public function make_code($name)
    {
        $name = preg_replace('/[^A-Za-z0-9]/', '', $name);        
        $name = strtolower($name);
        return $name;
    }

    public function addInLangFile($code,$value)
    {
        $selected_lang = UtilityRepository::get_setting('default_language');
        $path = base_path().'/resources/lang/'.$selected_lang.'/custom_profile.php';
        $content = file($path);
        

        $value    = addslashes($value);    
        $value    = str_replace('$', '\$', $value);
        $value    = str_replace("\'", "'", $value);
        $inserted = '"'.$code.'" => "'.$value.'",'."\n";

        for($i = 0 ; $i < count($content);$i++)
        {
            if(preg_match('/return.+/', $content[$i]))
                {
                    array_splice($content, $i+1, 0, $inserted);
                    file_put_contents($path, $content);
                }
        }
    }

}