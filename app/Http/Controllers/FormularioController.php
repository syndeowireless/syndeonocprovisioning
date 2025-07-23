<?php

namespace App\Http\Controllers;

use App\Models\Rom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RomGeneratorController extends Controller
{
    public function create()
    {
        return view("sales.rom-generator.create");
    }

    public function search()
    {
        return view("sales.rom-generator.search");
    }

    public function searchRom(Request $request)
    {
        $romCode = $request->input("rom_code");
        
        // Extrair o ID do código ROM (ex: ROM0001 -> 1)
        $romId = (int) str_replace("ROM", "", $romCode);
        
        $rom = Rom::find($romId);
        
        if (!$rom) {
            return redirect()->route("sales.rom-generator.search")
                ->with("error", "ROM Not Found");
        }
        
        return view("sales.rom-generator.found", compact("rom", "romCode"));
    }

    public function edit($id)
    {
        $rom = Rom::findOrFail($id);
        return view("sales.rom-generator.edit", compact("rom"));
    }

    public function update(Request $request, $id)
    {
        $rom = Rom::findOrFail($id);
        
        // Validação dos dados
        $validatedData = $request->validate([
            "property_name" => "required|string|max:255",
            "property_address" => "required|string",
            "property_type" => "required|string",
            "floors" => "required|integer|min:1",
            "buildings" => "required|integer|min:1",
            "parking_area" => "nullable|string",
            "coverage_area" => "nullable|string",
            "average_density" => "nullable|string",
            "construction_status" => "nullable|string",
            "system_type" => "nullable|string"
        ]);

        // Converter o valor do radio button para boolean
        $validatedData["connection_between_buildings"] = (bool)$request->connection_between_buildings;

        // Atualizar o registro
        $rom->update($validatedData);
        
        // Regenerar os arquivos
        $this->generateFiles($rom);
        
        $romCode = "ROM" . str_pad($rom->id, 4, "0", STR_PAD_LEFT);
        
        return redirect()->route("sales.rom-generator.create")
            ->with("show_modal", true)
            ->with("rom_code", $romCode)
            ->with("message", "ROM atualizada com sucesso!")
            ->with("rom_id", $rom->id);
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            "property_name" => "required|string|max:255",
            "property_address" => "required|string",
            "property_type" => "required|string",
            "floors" => "required|integer|min:1",
            "buildings" => "required|integer|min:1",
            "parking_area" => "nullable|string",
            "coverage_area" => "nullable|string",
            "average_density" => "nullable|string",
            "construction_status" => "nullable|string",
            "system_type" => "nullable|string"
        ]);

        // Converter o valor do radio button para boolean
        $validatedData["connection_between_buildings"] = (bool)$request->connection_between_buildings;

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

    public function download($id)
    {
        $rom = Rom::findOrFail($id);
        
        $relativePath = $rom->pptx_file_path; // Ex: public/roms/ROM0001.pptx

        // Usar o caminho absoluto para response()->download()
        $absoluteFilePath = storage_path("app/" . $relativePath);
        
        $romCode = "ROM" . str_pad($rom->id, 4, "0", STR_PAD_LEFT);
        
        return response()->download($absoluteFilePath, $romCode . ".pptx");
    }

    private function generateFiles(Rom $rom)
    {
        $romCode = "ROM" . str_pad($rom->id, 4, "0", STR_PAD_LEFT);
        
        // Preparar dados para o script Python
        $data = [
            "rom_code" => $romCode,
            "property_name" => $rom->property_name,
            "property_address" => $rom->property_address,
            "property_type" => $rom->property_type,
            "floors" => $rom->floors,
            "buildings" => $rom->buildings,
            "parking_area" => $rom->parking_area,
            "connection_between_buildings" => $rom->connection_between_buildings,
            "coverage_area" => $rom->coverage_area,
            "average_density" => $rom->average_density,
            "construction_status" => $rom->construction_status,
            "system_type" => $rom->system_type
        ];
        
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
