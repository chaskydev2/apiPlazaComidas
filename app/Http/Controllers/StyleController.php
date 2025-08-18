<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StyleController extends Controller
{
    public function edit()
    {
        $config = config('ui');
        return view('style.edit', [
            'navbar_bg' => $config['navbar_bg'] ?? '#df1518',
            'navbar_logo' => $config['navbar_logo'] ?? 'logo.png',
            'menu_title' => $config['menu_title'] ?? '🌟 Nuestro Menú 🌟',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'navbar_bg' => 'required|string',
            'navbar_logo' => 'nullable|image|max:2048',
            'menu_title' => 'required|string|max:100',
        ]);

        $logoPath = config('ui.navbar_logo', 'logo.png');
        if ($request->hasFile('navbar_logo')) {
            $file = $request->file('navbar_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path(), $filename);
            $logoPath = $filename;
        }

        // Actualizar config/ui.php
        $configPath = config_path('ui.php');
        $content = "<?php\nreturn [\n    'navbar_bg' => '" . $request->navbar_bg . "',\n    'navbar_logo' => '" . $logoPath . "',\n    'menu_title' => '" . addslashes($request->menu_title) . "',\n];\n";
        file_put_contents($configPath, $content);

        return redirect()->route('products.manage')->with('success', 'Estilo actualizado correctamente.');
    }
}
