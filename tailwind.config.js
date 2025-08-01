module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            screens: {
                xs: "475px",
            },
            fontFamily: {
                quicksand: ["Quicksand", "sans-serif"],
                inter: ["Inter", "sans-serif"],
                nunito: ["Nunito", "sans-serif"],
            },
            backgroundImage: {
                "custom-gradient":
                    "radial-gradient(circle at 25% 25%, #FFEFED, #BFF2FF)",
            },
            borderRadius: {
                xl: "8px",
            },
            boxShadow: {
                "inset-top": "inset 0 5px 5px -3px rgba(0, 0, 0, 0.5)",
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [require("flowbite/plugin")],
};
