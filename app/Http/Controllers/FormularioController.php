<?php
namespace App\Http\Controllers;

use App\Models\Rom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FormularioController extends Controller
{

    public function create()
{
    return view("formularios.create");
}


    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            "property_name" => "required|string|max:255",
            "oem" => "required|string|max:255",
            "property_address" => "required|string",
            "master_unit_quantity" => "required|integer|min:0",
            "bda_quantity" => "required|integer|min:0",
            "latitude" => "required|string|max:50",
            "longitude" => "required|string|max:50",
            "remote_unit_quantity" => "required|integer|min:0",
            "property_type" => "required|string",
            "average_density" => "required|string",
            "system_type" => "required|string"
        ]);

        // Criar o registro e obter o ID gerado
        $rom = Rom::create($validatedData);
        $romCode = "ROM" . str_pad($rom->id, 4, "0", STR_PAD_LEFT); // Formato: ROM0001

        // Gerar os arquivos PowerPoint e PDF
        $this->generateFiles($rom);

        // Redirecionar com o código ROM (ID formatado)
        return redirect()->route("sales.rom-generator.create")
            ->with("show_modal", true)
            ->with("rom_code", $romCode)
            ->with("rom_id", $rom->id);
    }

    public function update(Request $request, $id)
    {
        $rom = Rom::findOrFail($id);
        
        // Validação dos dados
        $validatedData = $request->validate([
            "property_name" => "required|string|max:255",
            "oem" => "required|string|max:255",
            "property_address" => "required|string",
            "master_unit_quantity" => "required|integer|min:0",
            "bda_quantity" => "required|integer|min:0",
            "latitude" => "required|string|max:50",
            "longitude" => "required|string|max:50",
            "remote_unit_quantity" => "required|integer|min:0",
            "property_type" => "required|string",
            "average_density" => "required|string",
            "system_type" => "required|string"
        ]);

        // Atualizar o registro
        $rom->update($validatedData);
        
        // Regenerar os arquivos
        $this->generateFiles($rom);
        
        $romCode = "ROM" . str_pad($rom->id, 4, "0", STR_PAD_LEFT);
        
        return redirect()->route("sales.rom-generator.create")
            ->with("show_modal", true)
            ->with("rom_code", $romCode)
            ->with("message", "ROM updated successfully!")
            ->with("rom_id", $rom->id);
    }

    private function generateFiles(Rom $rom)
    {
        $romCode = "ROM" . str_pad($rom->id, 4, "0", STR_PAD_LEFT);
        
        // Preparar dados para o script Python
        $data = [
            "rom_code" => $romCode,
            "property_name" => $rom->property_name,
            "oem" => $rom->oem,
            "property_address" => $rom->property_address,
            "master_unit_quantity" => $rom->master_unit_quantity,
            "bda_quantity" => $rom->bda_quantity,
            "latitude" => $rom->latitude,
            "longitude" => $rom->longitude,
            "remote_unit_quantity" => $rom->remote_unit_quantity,
            "property_type" => $rom->property_type,
            "average_density" => $rom->average_density,
            "system_type" => $rom->system_type
        ];
        
        // ... resto do código para gerar os arquivos ...
        // Criar arquivo JSON temporário
        $jsonPath = storage_path("app/temp_rom_" . $rom->id . ".json");
        file_put_contents($jsonPath, json_encode($data, JSON_UNESCAPED_UNICODE));
        
        // Definir caminhos dos arquivos de saída
        $pptxPath = storage_path("app/public/roms/" . $romCode . ".pptx");
        $templatePath = base_path("scripts/NG_PROPOSAL_MODEL.pptx");
        
        // Executar script Python
        $scriptPath = base_path("scripts/generate_pptx.py");
        // Usar o caminho completo para o executável Python
        $process = new Process(["C:\\Python313\\python.exe", $scriptPath, $jsonPath, $templatePath, $pptxPath]);
        $process->setTimeout(3600); // Aumenta o timeout para evitar problemas de tempo
        
        try {
            $process->mustRun();
            
            // Atualizar caminhos no banco de dados
            $rom->update([
                "pptx_file_path" => "public/roms/" . $romCode . ".pptx"
            ]);
            
            // Remover arquivo JSON temporário
            unlink($jsonPath);
            
        } catch (ProcessFailedException $exception) {
            // Log do erro completo, incluindo a saída de erro do Python
            
            \Log::error("Erro ao gerar PowerPoint: " . $exception->getMessage());
            \Log::error("Saída de erro do Python: " . $exception->getErrorOutput());
            
            // Remover arquivo JSON temporário
            if (file_exists($jsonPath)) {
                unlink($jsonPath);
            }
            
            throw new \Exception("Erro ao gerar apresentação PowerPoint.");
        }
    }
}