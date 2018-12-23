<?php

class OrderStatusFilter implements InterceptingFilter
{
	public function run(Http $http, array $queryFields, array $formFields)
	{
		return
        [
            'orderStatus' => new OrderStatus()
        ];
	}
}