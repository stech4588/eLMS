<template>
    <section class="learn-section" id="education">
        <div class="container">
            <h2 class="main-heading">What You Will Learn?</h2>

            <div class="card-grid">
                <!-- Section 1 -->
                <div class="learn-card">
                    <img class="card-image" src="/images/Mobile_pc.jpg" alt="Digital Skills" />
                    <div class="card-content">
                        <h3 class="card-title">Learn Relevant Digital Skills</h3>
                        <div class="separator"></div>
                        <p class="card-text">The <strong>Business Models</strong> we teach are:</p>
                        <ul class="card-list">
                            <li><strong>Completely online</strong></li>
                            <li><strong>Location-independent</strong></li>
                            <li><strong>Scalable to your skill level</strong></li>
                            <li><strong>Focused on growth markets (AI)</strong></li>
                        </ul>
                        <div class="card-icon-group">
                            <figure v-for="(icon, index) in icons1" :key="index" class="icon-box">
                                <img :src="icon.src" alt="icon" />
                                <figcaption>{{ icon.caption }}</figcaption>
                            </figure>
                        </div>
                        <p class="click-note">Click One To Learn More</p>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="learn-card">
                    <img class="card-image" src="/images/modern-market-strategies.jpg" alt="Modern Strategies" />
                    <div class="card-content">
                        <h3 class="card-title">Modern Market Strategies</h3>
                        <div class="separator"></div>
                        <p class="card-text">
                            The <strong>Modern Market Strategies</strong> we teach are:
                        </p>
                        <ul class="card-list">
                            <li><strong>Latest market trends</strong></li>
                            <li><strong>Results-oriented techniques</strong></li>
                            <li><strong>Time-saving tactics</strong></li>
                            <li><strong>Flexible to fit your goals</strong></li>
                        </ul>
                        <div class="card-icon-group">
                            <figure v-for="(icon, index) in icons2" :key="index" class="icon-box">
                                <img :src="icon.src" alt="icon" />
                                <figcaption>{{ icon.caption }}</figcaption>
                            </figure>
                        </div>
                        <p class="click-note">Click One To Learn More</p>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="learn-card">
                    <img class="card-image" src="/images/automate-your-business.webp" alt="Automation" />
                    <div class="card-content">
                        <h3 class="card-title">Automate & Grow Your Business</h3>
                        <div class="separator"></div>
                        <p class="card-text">
                            The <strong>methods for growing Business</strong> we teach are:
                        </p>
                        <ul class="card-list">
                            <li><strong>Outsource smartly</strong></li>
                            <li><strong>Focus on growth</strong></li>
                            <li><strong>Improve efficiency</strong></li>
                            <li><strong>Use modern automation</strong></li>
                        </ul>
                        <div class="card-icon-group">
                            <figure v-for="(icon, index) in icons3" :key="index" class="icon-box">
                                <img :src="icon.src" alt="icon" />
                                <figcaption>{{ icon.caption }}</figcaption>
                            </figure>
                        </div>
                        <p class="click-note">Click One To Learn More</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'WebTeaching',
    data() {
        return {
            icons1: [
                { src: '/images/1.png', caption: 'Digital Literacy' },
                { src: '/images/2.png', caption: 'Critical Thinking' },
                { src: '/images/3.png', caption: 'Problem-Solving' },
                { src: '/images/4.png', caption: 'Collaboration' },
                { src: '/images/5.png', caption: 'Creativity' },
            ],
            icons2: [
                { src: '/images/6.png', caption: 'Data Analytics' },
                { src: '/images/7.png', caption: 'Digital Marketing' },
            ],
            icons3: [
                { src: '/images/8.png', caption: 'Leadership' },
                { src: '/images/9.png', caption: 'Adaptability' },
                { src: '/images/10.png', caption: 'Negotiation' },
            ],
        };
    },
};
</script>

<style scoped>
* {
    font-family: 'Segoe UI', sans-serif;
    box-sizing: border-box;
}

.learn-section {
    background: linear-gradient(135deg, #f0f4ff, #e2e8f0);
    padding: 80px 20px;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
}

.container {
    max-width: 1300px;
    margin: auto;
    text-align: center;
}

.main-heading {
    background: linear-gradient(90deg, #4f46e5, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 51px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}

.learn-card {
    background: #1e1e1e;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.learn-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.6);
}

.card-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.card-content {
    padding: 20px;
    font-family: 'Segoe UI', sans-serif;
    text-align: left;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-title {
    font-size: 22px;
    color: #00bfff;
    margin-top: 5px;
}

.separator {
    width: 40px;
    height: 3px;
    background-color: #00bfff;
    margin: 10px 0;
}

.card-text {
    color: #e0e0e0;
    line-height: 24px;
    margin-bottom: 10px;
}

.card-list {
    list-style: none;
    padding: 0;
    margin: 0 0 15px 0;
}

.card-list li {
    position: relative;
    padding-left: 18px;
    margin-bottom: 8px;
    color: white;
    font-size: 14px;
    line-height: 22px;
}

.card-list li::before {
    content: '◆';
    position: absolute;
    left: 0;
    color: #00bfff;
    font-size: 10px;
}

.click-note {
    font-style: italic;
    margin-top: 15px;
    color: #aaa;
    text-align: center;
    font-size: 13px;
}

.card-icon-group {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 10px;
    margin-top: 10px;
    height: 230px;
}
@media(max-width:670px){
    .card-icon-group {
        height: 370px;
    }
}
@media(max-width:650px){
    .card-icon-group {
        height:100%;
    }
}
.icon-box {
    background-color: #1e1e1e;
    padding: 10px;
    width: 80px;
    height: 100px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
}

.icon-box img {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.icon-box figcaption {
    margin-top: 6px;
    font-size: 11px;
    text-align: center;
}

@media (max-width: 400px) {
    .main-heading {
        font-size: 24px;
    }

    .card-title {
        font-size: 18px;
    }

    .card-content {
        padding: 15px;
    }

    .card-image {
        height: 160px;
    }
}
</style>
