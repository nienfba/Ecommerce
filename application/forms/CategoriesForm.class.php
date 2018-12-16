<?php
class CategoriesForm extends Form
{
    public function build()
    {
        $this->addFormField('name');
        $this->addFormField('contents');
        $this->addFormField('id');
        $this->addFormField('originalpicture');
    }
}