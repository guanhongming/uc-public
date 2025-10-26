import { defineConfig } from 'vitepress'

export default defineConfig({

  title: "Technical Documentation",
  description: "Uni application one-stop shop",
  base: '/uc-public/', 

  themeConfig: {

    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/01-introduction/' }
    ],


    sidebar: [
      {
        text: '1. Introduction',
        collapsed: true,
        items: [
          { text: 'Overview & Purpose', link: '/guide/01-introduction/' }
        ]
      },
      {
        text: '2. Local Development',
        collapsed: true,
        items: [
          { text: 'Setup Guide', link: '/guide/02-local-development/' }
        ]
      },
      {
        text: '3. System Architecture',
        collapsed: true,
        items: [
          { text: 'High-Level Overview', link: '/guide/03-system-architecture/' }
        ]
      },
      {
        text: '4. Backend (Laravel)',
        collapsed: true,
        items: [
          { text: 'Deep Dive', link: '/guide/04-backend-laravel/' },
          { text: 'Authentication', link: '/guide/04-backend-laravel/authentication' },
          { text: 'Queues & Jobs', link: '/guide/04-backend-laravel/queues' }
        ]
      },
      {
        text: '5. Frontend (Vue.js)',
        collapsed: true,
        items: [
          { text: 'Deep Dive', link: '/guide/05-frontend-vue/' },
          { text: 'State Management', link: '/guide/05-frontend-vue/state-management' },
          { text: 'Routing', link: '/guide/05-frontend-vue/routing' }
        ]
      },
      {
        text: '6. Database Design',
        collapsed: true,
        items: [
          { text: 'Schema & ERD', link: '/guide/06-database-design/' }
        ]
      },
      {
        text: '7. API Reference',
        collapsed: true, 
        items: [
          { text: 'Overview', link: '/guide/07-api-reference/' },
          { text: 'Authentication API', link: '/guide/07-api-reference/auth' },
          { text: 'Users API', link: '/guide/07-api-reference/users' },
          // Add more API resource pages here
        ]
      },
      {
        text: '8. Key Features & Workflows',
        collapsed: true,
        items: [
          { text: 'Business Logic', link: '/guide/08-key-features/' }
        ]
      },
      {
        text: '9. DevOps & Deployment',
        collapsed: true,
        items: [
          { text: 'CI/CD & Infrastructure', link: '/guide/09-devops-cicd/' }
        ]
      },
      {
        text: '10. Server Maintenance',
        collapsed: true,
        items: [
          { text: 'Operations Guide', link: '/guide/10-server-maintenance/' }
        ]
      },
      {
        text: '11. Contributing',
        collapsed: true,
        items: [
          { text: 'How to Contribute', link: '/guide/11-contributing/' }
        ]
      },
      {
        text: '12. Appendix',
        collapsed: true,
        items: [
          { text: 'Credentials & Services', link: '/guide/12-appendix/' }
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/guanhongming/uc-public' }
    ]
  },

  vite: {
    postcss: {
      plugins: [],
    },
  }
})
