@extends('layouts.app')

@section('title', 'Sobre - Eventopia')

@section('content')
<div class="about-container" style="padding-top: 60px; padding-left:20px">
    <h1 class=" about-title">Sobre
        a Eventopia</h1>
    <p class="about-description">A Eventopia é a solução definitiva para gestão de eventos. Facilitamos o
        planeamento,
        organização e participação em eventos, proporcionando uma experiência colaborativa e inovadora.</p>

    <section class="about-section">
        <h2>Recursos Principais</h2>
        <ul>
            <li>Interface intuitiva e fácil de usar</li>
            <li>Funcionalidades colaborativas para organizadores e participantes</li>
            <li>Ferramentas analíticas para monitorizar o sucesso do evento</li>
        </ul>
    </section>

    <section class="about-team">
        <h2>A Nossa Equipa</h2>
        <p>Guilherme Santos, Francisco Lopes, Pedro Gomes e David Castro, estudantes de 3º ano de licenciatura em
            Engenharia Informática e de Computação na FEUP, são os inovadores por trás da Eventopia.</p>
    </section>

    <section class="about-testimonials">
        <h2>Testemunhos</h2>
        <blockquote>
            "Desde que comecei a usar a Eventopia, a organização dos meus eventos tornou-se muito mais simples e
            eficaz.
            É uma ferramenta indispensável!" - Ana Sousa, Organizadora de Eventos
        </blockquote>
        <blockquote>
            "A capacidade de gerir todos os aspectos do meu evento num único lugar mudou completamente o jogo para
            mim.
            Eventopia é top!" - João Pereira, Produtor de Festivais
        </blockquote>
    </section>
</div>
@endsection