<?php
class OrdersForm extends Form
{
    public function build()
    {
        $this->addFormField('date');
        $this->addFormField('status');
        $this->addFormField('comment');
        $this->addFormField('commentOld');
        $this->addFormField('dateShipped');
        $this->addFormField('datePayment');
        $this->addFormField('dateDelivery');
        $this->addFormField('id');
        $this->addFormField('customerId');
    }
}