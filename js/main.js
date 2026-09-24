
const recipes = [
    {
        id: 1,
        title: "Pancake Stack",
        category: "breakfast",
        image: "https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=500",
        description: "Delicious fluffy pancakes served with syrup and fresh berries."
    },
    {
        id: 2,
        title: "Chicken Biryani",
        category: "lunch",
        image: "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500",
        description: "Aromatic and spicy chicken biryani packed with authentic flavors."
    },
    {
        id: 3,
        title: "Chocolate Cake",
        category: "dessert",
        image: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500",
        description: "Rich, moist chocolate cake topped with dark chocolate frosting."
    }
];


function displayRecipes(items) {
    const container = document.getElementById('recipe-list');
    if (!container) return;
    
    container.innerHTML = items.map(recipe => `
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <img src="${recipe.image}" class="card-img-top" alt="${recipe.title}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <span class="badge bg-warning text-dark mb-2">${recipe.category.toUpperCase()}</span>
                    <h5 class="card-title fw-bold">${recipe.title}</h5>
                    <p class="card-text text-muted">${recipe.description}</p>
                </div>
            </div>
        </div>
    `).join('');
}


document.addEventListener("DOMContentLoaded", () => {
    displayRecipes(recipes);

    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            filterBtns.forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');

            const category = e.target.dataset.category;
            if (category === 'all') {
                displayRecipes(recipes);
            } else {
                const filtered = recipes.filter(item => item.category === category);
                displayRecipes(filtered);
            }
        });
    });
});