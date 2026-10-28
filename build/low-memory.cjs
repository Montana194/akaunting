const fs = require('node:fs');
const path = require('node:path');
const { spawnSync } = require('node:child_process');

const root = path.resolve(__dirname, '..');
process.chdir(root);
const config = fs.readFileSync('webpack.mix.js', 'utf8');
const entries = [...config.matchAll(/\.js\('[^']+', 'public\/([^']+)\.js'\)/g)]
    .map(match => match[1]);
entries.push('css/app');
entries.sort((a, b) => Number(b === 'js/settings/settings.min') - Number(a === 'js/settings/settings.min'));

const manifestPath = 'public/mix-manifest.json';
let manifest = fs.existsSync(manifestPath) ? JSON.parse(fs.readFileSync(manifestPath, 'utf8')) : {};

for (let offset = 0; offset < entries.length; offset += 3) {
    const batch = entries.slice(offset, offset + 3);
    process.stdout.write(`\nBuilding ${batch.join(', ')}\n`);
    const result = spawnSync(process.execPath, [
        '--max-old-space-size=512',
        'node_modules/webpack/bin/webpack.js',
        '--config=node_modules/laravel-mix/setup/webpack.config.js',
    ], {
        cwd: root,
        env: { ...process.env, MIX_ONLY: batch.join(','), NODE_OPTIONS: '--max-old-space-size=512' },
        stdio: 'inherit',
    });
    if (result.error) throw result.error;
    if (result.status !== 0) process.exit(result.status || 1);

    manifest = { ...manifest, ...JSON.parse(fs.readFileSync(manifestPath, 'utf8')) };
    fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 4));
}
