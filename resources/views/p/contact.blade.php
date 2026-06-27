@extends('layouts.base')

@section('title', '| Contact')

@section('content')
<main class="container py-5 text-white animate__animated animate__fadeIn">
    <!-- Header Banner -->
    <section class="glass-card rounded-3xl p-6 md:p-8 lg:p-10 mb-12 relative overflow-hidden text-center border border-white/10">
        <div class="max-w-2xl mx-auto py-4">
            <h1 class="fw-extrabold text-white mb-2 fs-2 leading-tight">Contactez-nous</h1>
            <p class="text-indigo-200 fs-5 mb-4 font-medium">Une question ou une suggestion ? Nous sommes là pour vous répondre</p>
        </div>
    </section>

    <div class="row g-4">
        <!-- Contact Information Card -->
        <div class="col-lg-5 col-xl-4">
            <div class="glass-card rounded-3xl p-4 p-md-5 border border-white/10 relative overflow-hidden h-100 flex flex-col justify-between">
                <div class="absolute -bottom-24 -right-24 w-60 h-60 bg-indigo-500/10 rounded-full filter blur-[60px] pointer-events-none"></div>
                
                <div>
                    <h3 class="fw-bold text-white mb-4 fs-5 border-b border-white/5 pb-2 text-indigo-400">Coordonnées</h3>
                    
                    <div class="d-flex flex-col gap-4 text-gray-300 mb-5">
                        <div class="d-flex align-items-start">
                            <span class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 d-flex justify-content-center align-items-center me-3 text-indigo-400">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <div>
                                <small class="text-gray-500 block text-[9px] uppercase font-bold tracking-wider">Adresse</small>
                                <span class="small">Agbalépédo, Lomé, Togo</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <span class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 d-flex justify-content-center align-items-center me-3 text-indigo-400">
                                <i class="fas fa-phone"></i>
                            </span>
                            <div>
                                <small class="text-gray-500 block text-[9px] uppercase font-bold tracking-wider">Téléphone</small>
                                <span class="small">+228 98 46 22 88</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <span class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 d-flex justify-content-center align-items-center me-3 text-indigo-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <div>
                                <small class="text-gray-500 block text-[9px] uppercase font-bold tracking-wider">E-mail</small>
                                <span class="small">contact@tgevent.com</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="fw-bold text-white mb-3 fs-6">Suivez-nous</h4>
                    <div class="d-flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="col-lg-7 col-xl-8">
            <div class="glass-card rounded-3xl p-4 p-md-5 border border-white/10 relative overflow-hidden">
                <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-500/10 rounded-full filter blur-[100px] pointer-events-none"></div>
                
                <h3 class="fw-bold text-white mb-4 fs-5 border-b border-white/5 pb-2 text-indigo-400">Envoyer un message</h3>
                
                <form onsubmit="event.preventDefault(); alert('Message envoyé avec succès ! Notre équipe reviendra vers vous très vite.');" class="needs-validation">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label text-gray-400 small fw-semibold">Nom complet</label>
                            <input type="text" id="name" required placeholder="Votre nom" class="form-control glass-input rounded-xl py-2.5 px-3">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label text-gray-400 small fw-semibold">Adresse Email</label>
                            <input type="email" id="email" required placeholder="Votre adresse email" class="form-control glass-input rounded-xl py-2.5 px-3">
                        </div>
                        
                        <div class="col-12">
                            <label for="subject" class="form-label text-gray-400 small fw-semibold">Sujet</label>
                            <input type="text" id="subject" required placeholder="Le sujet de votre message" class="form-control glass-input rounded-xl py-2.5 px-3">
                        </div>
                        
                        <div class="col-12">
                            <label for="message" class="form-label text-gray-400 small fw-semibold">Message</label>
                            <textarea id="message" required rows="5" placeholder="Votre message..." class="form-control glass-input rounded-xl py-2.5 px-3"></textarea>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn text-white font-semibold rounded-xl py-3 hover:scale-105 transition-all duration-300 border-0" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                            Envoyer le message <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
