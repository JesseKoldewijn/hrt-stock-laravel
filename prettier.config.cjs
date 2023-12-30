module.exports = {
    plugins: ["@prettier/plugin-php"],

    // prettier options
    printWidth: 100,
    tabWidth: 2,
    useTabs: false,
    singleQuote: false,
    trailingComma: "all",
    bracketSpacing: true,
    jsxBracketSameLine: true,
    arrowParens: "always",
    requirePragma: false,
    insertPragma: false,
    proseWrap: "preserve",

    // php options
    phpVersion: "8.2",
};
