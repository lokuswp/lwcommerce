const lightCodeTheme = require('prism-react-renderer/themes/github');
const darkCodeTheme = require('prism-react-renderer/themes/dracula');

// With JSDoc @type annotations, IDEs can provide config autocompletion
/** @type {import('@docusaurus/types').DocusaurusConfig} */
(module.exports = {
    title: 'LWPCommerce',
    tagline: 'Hybrid Ecommerce Plugin WordPress',
    url: 'https://617bbb486961c5b2d772df7f--priceless-tereshkova-b95c3e.netlify.app/',
    baseUrl: '/',
    onBrokenLinks: 'ignore',
    onBrokenMarkdownLinks: 'warn',
    favicon: 'img/favicon.ico',
    organizationName: 'lokuswp', // Usually your GitHub org/user name.
    projectName: 'lwcommerce', // Usually your repo name.
    trailingSlash: false,

    presets: [
        [
            '@docusaurus/preset-classic',
            /** @type {import('@docusaurus/preset-classic').Options} */
            ({
                docs: {
                    sidebarPath: require.resolve('./sidebars.js'),
                    routeBasePath: '/',
                    // Please change this to your repo.
                    editUrl: 'https://github.com/lokuswp/lokuswp-backbone/edit/main/documentation/',
                },
                blog: {
                    showReadingTime: true,
                    // Please change this to your repo.
                    editUrl: 'https://github.com/lokuswp/lokuswp-backbone/edit/main/documentation/blog/',
                },
                theme: {
                    customCss: require.resolve('./src/css/custom.css'),
                },
            }),
        ],
    ],

    /** @type {import('@docusaurus/preset-classic').ThemeConfig} */
    themeConfig: {
        // colorMode: {
        //     defaultMode: 'dark',
        //     disableSwitch: true,
        // },
        navbar: {
            title: 'LWPCommerce',
            logo: {
                alt: 'LWPCommerce',
                src: 'img/lwcommerce.png',
            },
            items: [{
                    type: 'doc',
                    docId: 'intro',
                    position: 'left',
                    label: 'Documentation',
                },
                {
                    href: 'https://documenter.getpostman.com/view/18636322/UVJkCDux',
                    position: 'left',
                    label: 'Postman',
                },
                {
                    href: 'https://github.com/lokuswp/lokuswp-backbone',
                    label: 'GitHub',
                    position: 'right',
                },
            ],
        },
        prism: {
            theme: darkCodeTheme,
            darkTheme: darkCodeTheme,
            additionalLanguages: ['php'],
        },
   
    }
});