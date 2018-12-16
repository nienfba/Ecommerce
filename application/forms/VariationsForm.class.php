<?php
class VariationsForm extends Form
{
    public function build()
    {
        $this->addFormField('name');
        $this->addFormField('price');
        $this->addFormField('quantity');
        $this->addFormField('id');
        $this->addFormField('productid');
    }
}