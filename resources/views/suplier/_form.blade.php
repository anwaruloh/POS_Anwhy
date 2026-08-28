<div>
    <label>Nama Suplier</label><br>
    <input type="text" name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $suplier->nama_suplier ?? '') }}">
    @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div>
    <label>Alamat</label><br>
    <input type="text" name="alamat"
           class="form-control @error('alamat') is-invalid @enderror"
           value="{{ old('alamat', $suplier->alamat ?? '') }}">
    @error('alamat')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div>
    <label>No. Telp</label><br>
    <input type="string" name="no_telp"
           class="form-control @error('no_telp') is-invalid @enderror"
           value="{{ old('no_telp', $suplier->no_telp ?? '') }}">
    @error('no_telp')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<button class="btn btn-success mt-3" type="submit">Simpan</button>
