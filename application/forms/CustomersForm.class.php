<?php
class CustomersForm extends Form
{
    public function build()
    {
        $this->addFormField('firstname');
        $this->addFormField('lastname');
        $this->addFormField('email');
        $this->addFormField('address');
        $this->addFormField('cp');
        $this->addFormField('city');
        $this->addFormField('country');
        $this->addFormField('phone');
        $this->addFormField('birthdate');
        $this->addFormField('id');
    }
}