<template>
    <section class="see-results">
        <!-- Background glow effect -->
        <div class="radial-bg"></div>

        <div class="results-container">
            <div class="results-heading">
                <h2>See Real Results</h2>
            </div>

            <div class="results-description">
                <p>
                    We believe in the value of our online educational platform. If you genuinely
                    <strong>put in 100% effort</strong> and don’t feel you’ve gained value, feel free to cancel at any
                    time.
                </p>
                <p>
                    That’s why we’re priced at a <strong>fraction of the cost</strong> of traditional educational
                    institutions.
                    The difference? You don’t have to wait four years to get a degree and look for a job…
                </p>
                <p>
                    Our focus is to help you <strong>earn while you learn.</strong> We teach the realities of business
                    through
                    daily action.
                </p>
            </div>

            <div class="cards-wrapper">
                <div class="result-card" v-for="(card, index) in cards" :key="index">
                    <div class="img-container">
                        <img :src="card.image" :alt="'Image ' + index" />
                    </div>
                    <div class="card-text">
                        <p>{{ card.text }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'WebResults',
    data() {
        return {
            cards: [
                {
                    image: '/images/the-real-world-app.webp',
                    text:
                        'We’ve built an ever-accessible portal, available anytime, anywhere. Use our custom application on any computer, smartphone, or tablet.',
                },
                {
                    image: '/images/200k-students.png',
                    text:
                        'Over 200,000+ students have joined. Real transformation comes with real community, guidance, and systems that work.',
                },
                {
                    image: '/images/luxury-lifestyle.jpg',
                    text:
                        'Our students not only learn but live the benefits. Freedom, income, and lifestyle changes are already happening.',
                },
            ],
        };
    },
};
</script>

<style scoped>
.see-results {
    position: relative;
    background-color: #E4EAF2;
    color: #fff;
    font-family: 'Segoe UI', sans-serif;
    padding: 60px 20px;
    overflow: hidden;
}

.radial-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, #c7d2fe 0%, transparent 70%);
    opacity: 0.3;
    z-index: 0;
}

.results-container {
    position: relative;
    z-index: 1;
    max-width: 1200px;
    margin: auto;
    text-align: center;
}

.results-heading h2 {
    background: linear-gradient(90deg, #4f46e5, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
}

@media (max-width: 600px) {
    .results-heading h2 {
        font-size: 28px;
    }
}

.results-description {
    font-size: 18px;
    line-height: 1.8;
    max-width: 800px;
    text-align: center;
    color: black;
    margin: 0 auto 40px;
}

.results-description p {
    font-family: 'Segoe UI', sans-serif;
    font-size: 1.2rem;
    color: #374151;
    max-width: 700px;
    margin: 0 auto 30px;
    line-height: 1.7;
}

@media (max-width: 768px) {
    .results-description {
        font-size: 15px;
        text-align: center;
    }
}

.cards-wrapper {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

.result-card {
    background-color: #1e1e2a;
    border-radius: 16px;
    overflow: hidden;
    width: 350px;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    position: relative;
}

.result-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
    transition: left 0.5s;
    z-index: 1;
}

.result-card:hover::before {
    left: 100%;
}

.result-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
}

.img-container {
    width: 100%;
    height: 220px;
    overflow: hidden;
    border-bottom: 2px solid #fff;
}

.img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 16px 16px 0 0;
}

.card-text {
    padding: 20px;
    text-align: left;
    font-size: 15px;
    line-height: 1.6;
}

@media (max-width: 600px) {
    .card-text {
        text-align: center;
    }
}
</style>
