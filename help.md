/game_store_project
│
├── /config
│   └── db.php                 # Database connection settings (PDO)
│
├── /assets
│   ├── /css
│   │   └── style.css          # Main styling, Responsive, Dark/Light mode
│   ├── /js
│   │   └── main.js            # AJAX for filters, Cart logic, Animations
│   └── /images
│       └── (You will place game images here)
│
├── /includes
│   ├── header.php             # Navigation bar, Session start, Meta tags
│   ├── footer.php             # Closing tags, Script includes
│   └── functions.php          # Security helpers, Input sanitization
│
├── /api
│   ├── auth.php               # Backend: Login, Register, Logout
│   ├── cart_api.php           # Backend: Add to cart, Remove, Checkout
│   └── products.php           # Backend: JSON output for games (Search/Filter)
│
├── /pages
│   ├── home.php               # Landing page (Featured games)
│   ├── login.php              # Login form
│   ├── register.php           # Registration form
│   ├── catalog.php            # All Games with Filters and Sorting
│   ├── product.php            # Game Details page
│   ├── cart.php               # Cart view
│   ├── checkout.php           # Checkout summary
│   ├── dashboard.php          # User Profile & Order History
│   └── admin.php              # Admin Panel (Add/Edit Games)
│
├── index.php                  # Main Entry point (Router)
└── database.sql               # SQL Schema for Users, Games, Orders