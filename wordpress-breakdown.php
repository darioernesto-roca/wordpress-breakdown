<?php

/*

    1. WordPress Breakdow. Concepts and definitions.

*/ {
    // 1.1 What is WordPress? - WordPress is a content management system (CMS) that allows users to create and manage websites without needing to know how to code. It is open-source software that is free to use and has a large community of developers who contribute to its development and support.

    // PHP: WordPress is built using PHP, which is a server-side scripting language. This allows WordPress to generate dynamic content and interact with databases. PHP is a scripting language primarily used for web development. It's embedded within HTML, meaning you can weave PHP code directly into your web pages. When a user requests a page containing PHP code, the server processes the PHP, generates HTML, and sends that HTML to the user's browser. This allows for dynamic content generation, database interaction, and handling user input, making websites interactive and personalized.

    // SQL: WordPress uses MySQL as its database management system. This allows WordPress to store and retrieve data such as posts, pages, and user information. It can also use other database management systems such as MariaDB and PostgreSQL.

}

/*
    2. WordPress Concepts
*/ {
    // 2.1 Themes - A theme is a collection of files that determine the look and feel of a WordPress website. It includes templates, stylesheets, and images that control the layout and design of the site.

    // 2.2 Plugins - A plugin is a piece of software that adds functionality to a WordPress website. It can be used to add features such as contact forms, social media integration, and e-commerce capabilities.

    // 2.3 Widgets - A widget is a small block of content that can be added to a WordPress website's sidebar or footer. It can be used to display information such as recent posts, categories, and search bars.

}

/*
    3. Installing WordPress
*/ {
    // 3.1 Download WordPress - You can download the latest version of WordPress from the official website (https://wordpress.org/download/).

    // 3.2 Create a Database - You will need to create a database for your WordPress installation. This can be done using a tool such as phpMyAdmin or through the command line.

    // 3.3 Upload WordPress Files - You will need to upload the WordPress files to your web server. This can be done using an FTP client or through the command line.

    // 3.4 Run the Installation Script - Once you have uploaded the WordPress files, you can run the installation script by navigating to your website in a web browser. Follow the prompts to complete the installation process.

    // 3.5 LocalWP: LocalWP is a local development environment for WordPress. It allows you to create and manage WordPress sites on your local machine without needing to set up a web server or database. LocalWP provides a user-friendly interface for creating and managing WordPress sites, making it easier for developers to work on their projects locally before deploying them to a live server.

    // 3.6 XAMPP: XAMPP is a free and open-source cross-platform web server solution stack package, consisting primarily of the Apache HTTP Server, MariaDB database, and interpreters for scripts written in the PHP and Perl programming languages. It allows you to create a local server environment on your computer, enabling you to develop and test websites and web applications before deploying them to a live server. This is particularly useful for WordPress development, as it provides a convenient way to build and experiment with WordPress sites offline.

    // 3.7 VVV: VVV (Varying Vagrant Vagrants) is an open-source development environment specifically designed for WordPress. It provides a pre-configured virtual machine that includes everything you need to develop WordPress themes, plugins, and even WordPress core itself. This allows developers to create a consistent and isolated environment, minimizing compatibility issues and streamlining the development process.

    // 3.8 Docker for Local WordPress Development: Docker is a platform that uses containerization to package software with all its dependencies into standardized units for software development. This allows developers to create, deploy, and run applications in isolated environments called containers, ensuring consistency across different computing environments, from development to production. For WordPress, Docker simplifies setting up a local development environment by providing pre-configured containers with all the necessary components like web servers, databases, and PHP.
    // For more information https://www.digitalocean.com/community/tutorials/how-to-install-wordpress-with-docker-compose
}

/* 
    4. WordPress Concepts
*/

{
    // 4.1 Themes: A theme is a collection of files that determine the look and feel of a WordPress website. It includes templates, stylesheets, and images that control the layout and design of the site. Themes can be customized to fit the specific needs of a website, and there are thousands of free and premium themes available for WordPress. Themes in WordPress control the overall design and appearance of your website. They dictate the layout, colors, fonts, and styles used to present your content to visitors. Think of them as pre-designed templates that you can customize to create a unique look for your site without needing to write code from scratch.

    // 4.2 Block Themes: Block themes are a new type of WordPress theme that is built using the block editor. They allow users to create custom layouts and designs using blocks, which are reusable components that can be added to posts and pages. Block themes provide more flexibility and customization options than traditional themes, and they are designed to work seamlessly with the block editor. Block themes in WordPress are a modern approach to website design, where the entire site structure, from header to footer, is built using blocks. These blocks, the same ones used to create content in the WordPress editor, offer a visual and intuitive way to customize every aspect of your site's appearance without needing to write code. This allows for greater flexibility and control over design, empowering users to create unique and personalized websites.

    // 4.3 Classic Themes represent the traditional way of designing and structuring a WordPress website, relying heavily on PHP, HTML, CSS, and JavaScript within the theme files themselves. These themes typically involve a more direct approach to coding and customization, often requiring developers to modify template files directly to alter the site's appearance and functionality. They offer a high degree of control but can also be more complex to manage and update compared to newer theme types.

     // 4.4 Child Themes: A child theme is a theme that inherits the functionality and styling of another theme, called the parent theme. Child themes allow users to make customizations to a website without modifying the parent theme's files, which can be useful for maintaining updates and ensuring compatibility with future versions of WordPress. By creating a child theme, you can safely customize your site while still benefiting from the features and updates of the parent theme.

     // 4.5 Hybrid Themes: Hybrid themes in WordPress combine the best aspects of traditional themes and block themes. They allow developers to use both classic PHP templates and the block editor for different parts of their website, offering flexibility in design and development. However, they are not built to use the Site Editor. This approach enables a gradual transition to block-based theming while still leveraging existing theme structures and code.

     // 4.6 Third-Party: Third-party WordPress themes are pre-designed website templates created by developers or companies other than WordPress.org. These themes offer a wide range of designs and functionalities, allowing users to quickly customize the look and feel of their WordPress website without extensive coding knowledge. They are typically purchased or downloaded from independent marketplaces or theme developers' websites.

     // 4.7 Plugins: WordPress plugins are like add-ons for your WordPress website. They are pieces of software that you can upload and install to extend the functionality of your site. Think of them as apps for your website, allowing you to add features like contact forms, e-commerce capabilities, SEO tools, social media integration, and much more, without needing to write any code yourself.

     // 4.8 WordPress File Structure: The WordPress file structure is the organized system of folders and files that make up a WordPress website. It includes core WordPress files, theme files, plugin files, and user-uploaded content, all working together to display your website's content and functionality. Understanding this structure is essential for troubleshooting, customizing, and managing your WordPress site effectively.

     // 4.9 Page Editors: Page editors are tools that allow users to create and modify the content and layout of individual pages on a website through a visual interface. They simplify the process of web design and content creation by providing a user-friendly way to arrange elements like text, images, and other media without needing to write code. These editors often use a drag-and-drop interface, making it easy to build and customize pages to suit specific needs.
}

/* 
        5. Hooks
*/

{
    // 5.1 Hooks: Hooks are a way for developers to modify the behavior of WordPress without changing the core code. They allow developers to add custom functionality to WordPress by "hooking" into specific points in the WordPress code. There are two types of hooks: actions and filters. Actions allow developers to execute custom code at specific points in the WordPress code, while filters allow developers to modify data before it is displayed on the website.

    // 5.2 Actions: Actions are a type of hook that allows developers to execute custom code at specific points in the WordPress code. They are used to perform tasks such as adding new functionality, modifying existing functionality, or triggering events. For example, you can use an action hook to send an email notification when a new post is published.

        // Examples of action hooks include 'init', which runs after WordPress has finished loading but before any headers are sent, and 'wp_footer', which runs just before the closing </body> tag in the theme. By using these hooks, developers can add custom code to enhance the functionality of their WordPress site without modifying core files, ensuring that updates to WordPress do not overwrite their customizations.

        // Here's the most used actions in WordPress:
        // 'init' - This action is triggered after WordPress has finished loading but before any
        // headers are sent. It is commonly used to initialize plugins, register custom post types, and set up theme features.
        // 'wp_enqueue_scripts' - This action is used to enqueue stylesheets and JavaScript
        // files for the front-end of the website. It allows developers to properly load their assets and manage dependencies.
        // 'admin_enqueue_scripts' - Similar to 'wp_enqueue_scripts', this action is used to enqueue stylesheets and JavaScript files for the WordPress admin area. It ensures that custom assets are loaded only in the admin interface, preventing unnecessary loading on the front-end.
        // 'wp_footer' - This action is triggered just before the closing </body> tag
        // in the theme. It is commonly used to add custom scripts or content that should appear at the end of the page, such as analytics tracking code or custom JavaScript.

    // 5.3 Filters: Filters are a type of hook that allows developers to modify data before it is displayed on the website. They are used to change the output of WordPress functions, such as modifying the content of a post or changing the title of a page. For example, you can use a filter hook to add a custom message to the end of every post.

        // Here are some commonly used filter hooks in WordPress:
        // 'the_content' - This filter allows developers to modify the content of a post before it is displayed on the front-end. It can be used to add custom formatting, insert additional content, or modify the existing content in various ways.
        // 'the_title' - This filter allows developers to modify the title of a post or page before it is displayed. It can be used to add prefixes, suffixes, or completely change the title based on specific conditions.

     // 5.4 Custom Hooks: Custom hooks are user-defined hooks that allow developers to create their own actions and filters. They can be used to create custom functionality that is specific to a particular website or plugin. Custom hooks provide a way for developers to extend WordPress in unique ways that may not be covered by existing hooks.
}

/* 6. The Loop */

{
    // 6.1 The Loop: The Loop is a fundamental concept in WordPress that is used to display posts and pages on a website. It is a PHP code structure that retrieves and displays content from the WordPress database. The Loop is used in theme files to control how posts and pages are displayed on the front-end of a website.

    // The Loop works by checking if there are any posts or pages to display, and if so, it retrieves the content and displays it according to the specified template. The Loop can be customized to display different types of content, such as custom post types or specific categories, and it can also be used to control the layout and design of the content being displayed.
    // The loop is a PHP code structure typically found in WordPress theme files. It checks if there are posts to display and iterates through them, outputting the content according to the theme's design. The loop can be customized with parameters to filter posts by category, tag, author, or other criteria, allowing developers to create dynamic and flexible content displays. Examples of loop functions include `have_posts()`, which checks if there are any posts to display, and `the_post()`, which sets up the post data for use in template tags. By using the loop, developers can create custom layouts and presentations for their WordPress content, enhancing the user experience on their websites. Those functions are usually located in the theme's template files, such as `index.php`, `single.php`, and `archive.php`, and can be modified to suit the specific needs of the website.
    // The Loop is PHP code used by WordPress to display posts. Using The Loop, WordPress processes each post to be displayed on the current page, and formats it according to how it matches specified criteria within The Loop tags. Any HTML or PHP code in the Loop will be processed on each post.

    // When WordPress documentation says "This tag must be within The Loop", such as for specific Template Tags or plugins, the tag will be repeated for each post. For example, The Loop displays the following information by default for each post:

    // Title (the_title())
    // Time (the_time())
    // Categories (the_category()).
    // You can display other information about each post using the appropriate Template Tags or (for advanced users) by accessing the $post variable, which is set with the current post's information while The Loop is running.
}

/* 7. Shortcodes */

{
    // 7.1 Shortcodes: Shortcodes are a way for users to add custom functionality to their WordPress posts and pages without needing to write code. They are small pieces of code that can be inserted into the content of a post or page, and they will be replaced with the corresponding output when the page is displayed. Shortcodes can be used to add features such as contact forms, image galleries, and social media integration.

    // Shortcodes in WordPress are a powerful tool that allows users to easily add dynamic content to their posts and pages without needing to write complex code. They are essentially placeholders that can be replaced with specific functionality or content when the page is rendered. For example, a shortcode like [gallery] can be used to display an image gallery, while [contact-form] might display a contact form. Shortcodes can be created by developers to provide custom functionality, and they can also be provided by plugins to extend the capabilities of a WordPress site. By using shortcodes, users can enhance their content with interactive elements and features that would otherwise require coding knowledge.

    // The way a shortcode works is that when WordPress encounters a shortcode in the content, it looks for a corresponding function that defines what the shortcode should do. This function is typically registered using the `add_shortcode()` function in WordPress. When the shortcode is processed, WordPress calls the associated function, which generates the output that replaces the shortcode in the content. This allows for a wide range of functionality to be added to posts and pages simply by using shortcodes, making it easier for users to enhance their content without needing to understand the underlying code. When a user adds a shortcode to the content, WordPress uses the `do_shortcode()` function to process the shortcode and generate the appropriate output. This function parses the content for any registered shortcodes and replaces them with the output generated by their corresponding functions. This mechanism allows for a seamless integration of dynamic content and features into WordPress posts and pages, making it a popular choice for users looking to enhance their websites without needing to write custom code.

    // In WordPress architecture, shortcodes are handled in files such as `wp-includes/shortcodes.php`, where the core functionality for processing shortcodes is defined. This file contains the necessary functions to register, manage, and execute shortcodes, allowing developers to create custom shortcodes that can be used throughout their WordPress site. By leveraging the shortcode API provided in this file, developers can easily extend the capabilities of their WordPress sites and provide users with a simple way to add dynamic content to their posts and pages. This is a core part of the WordPress architecture that enables the flexibility and extensibility that WordPress is known for, allowing users to enhance their websites with a wide range of features and functionalities through the use of shortcodes.
}

/* 8. Style & Script Enqueueing */

{
    // Style and script enqueueing is the proper way to add CSS stylesheets and JavaScript files to your WordPress website. Instead of directly adding code to your theme's header or footer, enqueueing uses WordPress functions to register and load these assets in a controlled and organized manner. This ensures compatibility, avoids conflicts with other plugins and themes, and allows for dependency management.
}

/* 9. Custom Post Types */

{
    // Custom Post Types allow you to create different types of content beyond the standard posts and pages. Think of them as a way to organize and manage specific kinds of information on your website, like products, events, or testimonials, each with its own unique set of fields and display options. This helps you structure your website's content in a more meaningful and organized way.

    // Custom Post Types in WordPress are a powerful feature that allows developers to create and manage different types of content beyond the default posts and pages. By registering a custom post type, you can define specific attributes, such as custom fields, taxonomies, and templates, that are tailored to the unique needs of your content. This enables you to organize and display your content in a more structured and meaningful way, enhancing the user experience on your website. For example, if you're running an online store, you could create a custom post type for products, allowing you to manage product information separately from regular blog posts or pages. This flexibility is one of the key reasons why WordPress is such a popular choice for building websites of all kinds.

    // For example:
}

/* 10. Taxonomies */

{
    // Taxonomies are ways to group content. Think of them as organizational tools that help you classify and structure your website's information. They allow you to create relationships between different pieces of content, making it easier for users to navigate and find what they're looking for. Common examples include categories and tags, but you can also create custom taxonomies to suit your specific needs.

    // For example, if you have a website about books, you might use categories to group books by genre (e.g., Fiction, Non-Fiction, Mystery) and tags to describe specific attributes of each book (e.g., Author, Publication Year, Awards). This way, users can easily browse through your content based on their interests and preferences. Taxonomies in WordPress are a way to classify and organize content into groups or categories. They help users navigate and find related content on a website more easily. WordPress comes with built-in taxonomies like categories and tags, but developers can also create custom taxonomies to suit the specific needs of their website. By using taxonomies effectively, you can improve the user experience and make your content more discoverable.
}