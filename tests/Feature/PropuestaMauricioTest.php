<?php

test('propuesta mauricio is publicly accessible', function () {
    $this->get('/propuesta/mauricio')
        ->assertOk()
        ->assertSee('Propuesta ERP Escolar', false);
});
