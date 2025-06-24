<template>
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-container">
            <div class="hero-text">
                <h1>Welcome to MBM University</h1>
                <p>
                    Your gateway to quality education and professional development.
                    Discover courses that will help you achieve your goals.
                </p>
                <div class="hero-buttons">
                    <a href="/courses" class="btn-primary">Browse Courses</a>
                    <a href="/about" class="btn-secondary">Learn More</a>
                </div>
            </div>

            <div class="features">
                <div class="feature-card">
                    <div class="icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3>Expert-Led Courses</h3>
                    <p>Learn from industry professionals and experienced educators.</p>
                </div>

                <div class="feature-card">
                    <div class="icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3>Secure Learning</h3>
                    <p>Your progress and achievements are safely stored and verified.</p>
                </div>

                <div class="feature-card">
                    <div class="icon-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3>Flexible Learning</h3>
                    <p>Study at your own pace, anytime and anywhere.</p>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.hero-section {
    position: relative;
    padding: 100px 20px;
    background: linear-gradient(120deg, #f0f4ff, #e5eaff);
    overflow: hidden;
    font-family: 'Segoe UI', sans-serif;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, #c7d2fe 0%, transparent 70%);
    opacity: 0.3;
    z-index: 0;
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.hero-text {
    text-align: center;
    margin-bottom: 60px;
}

.hero-text h1 {
    background: linear-gradient(90deg, #4f46e5, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.hero-text p {
    font-size: 1.2rem;
    color: #374151;
    max-width: 700px;
    margin: 0 auto 30px;
    line-height: 1.7;
}

.hero-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}

.btn-primary {
    background: #3b82f6;
    color: white;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
    box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4);
}

.btn-primary:hover {
    background: #1d4ed8;
}

.btn-secondary {
    border: 2px solid #3b82f6;
    color: #3b82f6;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.btn-secondary:hover {
    background: #dbeafe;
}

.features {
    display: grid;
    grid-template-columns: 1fr;
    gap: 25px;
}

@media (min-width: 768px) {
    .features {
        grid-template-columns: repeat(3, 1fr);
    }
}

.feature-card {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 20px;
    text-align: left;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
}

.icon-box {
    color: #3b82f6;
    margin-bottom: 20px;
}

.icon-box svg {
    width: 50px;
    height: 50px;
}

.feature-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 10px;
}

.feature-card p {
    color: #4b5563;
    font-size: 0.95rem;
    line-height: 1.6;
}
</style>
