<?php
class ProductsForm extends Form
{
    public function build()
    {
        $this->addFormField('name');
        $this->addFormField('subtitle');
        $this->addFormField('description');
        $this->addFormField('price');
        $this->addFormField('tva');
        $this->addFormField('id');
        $this->addFormField('categoryId');
        $this->addFormField('originalpicture');
    }
}