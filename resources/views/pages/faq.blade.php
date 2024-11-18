@extends('layouts.app')

@section('title', 'Perguntas Frequentes - Eventopia')

@section('content')
<div class="faq-container">
    <h1 class="faq-title">Perguntas Frequentes</h1>
    <div class="faq-questions">

        <div class="faq-question">
            <h2>Como posso criar um evento na Eventopia?</h2>
            <p>Para criar um evento, aceda à sua dashboard, selecione 'Criar Evento' e preencha o formulário com as
                informações do seu evento.</p>
        </div>

        <div class="faq-question">
            <h2>Existem limites para o número de participantes por evento?</h2>
            <p>Não, a Eventopia suporta eventos de qualquer tamanho, desde reuniões íntimas a grandes festivais.</p>
        </div>
    </div>
</div>
@endsection