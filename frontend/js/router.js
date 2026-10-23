var app = $.spapp({
    defaultView  : "dashboard",
    templateDir  : "./views/"
  });

app.route({
    view: 'dashboard',
    load: 'dashboard.html',
    onReady: function () {
        window.initializeAreaChart();
        window.initializeBarChart();
    }
});
app.route({ view: 'layout-static', load: 'layout-static.html' });
app.route({ view: 'layout-sidenav-light', load: 'layout-sidenav-light.html'});
app.route({ view: 'login', load: 'login.html',onReady:function(){console.log('This is the login page.')}});
app.route({ view: 'register', load: 'register.html',onReady:function(){console.log('This is the registration page.')}});
app.route({
    view: 'profile',
    load: 'profile.html',
    onReady: function () {
        window.loadProfileForm();
    }
});
app.route({ view: 'add-item', load: 'add-item.html' });
app.route({ view: 'password', load: 'password.html',onReady:function(){console.log('This is the forgot password page.')}});
app.route({
    view: 'shop',
    load: 'shop.html',
    onReady: function () {
        const categoryButtons = document.querySelectorAll('#shop button[data-category]');
        const products = document.querySelectorAll('#shop .shop-product-card');

        categoryButtons.forEach(button => {
            button.addEventListener('click', function () {
                const selectedCategory = this.dataset.category;

                categoryButtons.forEach(categoryButton => {
                    const isSelected = categoryButton === this;
                    categoryButton.classList.toggle('btn-dark', isSelected);
                    categoryButton.classList.toggle('btn-outline-dark', !isSelected);
                    categoryButton.setAttribute('aria-pressed', String(isSelected));
                });

                products.forEach(product => {
                    const productColumn = product.closest('[data-category]');
                    productColumn.hidden = selectedCategory !== 'all' && productColumn.dataset.category !== selectedCategory;
                });
            });
        });
    }
});
app.route({ view: 'item', load: 'item.html' });
app.route({ view: '401', load: '401.html',onReady:function(){console.log('This is the 401 error page.')}});
app.route({ view: '404', load: '404.html',onReady:function(){console.log('This is the 404 error page.')}});
app.route({ view: '500', load: '500.html',onReady:function(){console.log('This is the 500 error page.')}});
app.route({
    view: 'charts',
    load: 'charts.html',
    onReady: function () {
        window.initializeAreaChart();
        window.initializeBarChart();
    }
});
app.route({ view: 'tables', load: 'tables.html'});
app.run();


document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.task-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const task = this.closest('.task');
            if (this.checked) {
                task.classList.add('completed');
            } else {
                task.classList.remove('completed');
            }
        });
    });
});
