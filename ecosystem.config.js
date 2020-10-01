module.exports = {
  apps: [
    {
      name              : 'wt',
      script            : 'dist/ssr/index.js',
      watch             : 'dist',
      max_memory_restart: '200M',
    },
  ],
};
