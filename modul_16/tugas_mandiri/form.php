<?php
$hobbyList = ["Membaca", "Olahraga", "Menyanyi", "Menari", "Traveling"];
$ekskulList = ["Pramuka", "Basket", "Volly", "Band", "Seni Tari", "Robotic", "Bulu Tangkis", "Renang"];

$selectedHobi = isset($data["hobi"]) ? (is_array($data["hobi"]) ? $data["hobi"] : array_map("trim", explode(",", $data["hobi"]))) : [];
$selectedEkskul = isset($data["ekskul"]) ? (is_array($data["ekskul"]) ? $data["ekskul"] : array_map("trim", explode(",", $data["ekskul"]))) : [];

$ttlParts = ["", "", ""];
if (!empty($data["ttl"])) {
  $ttlParts = explode("-", $data["ttl"]);
}
?>

<div class="form-box">
  <h2>Pendaftaran Ekstrakurikuler</h2>
  <hr />

  <table>
    <tr>
      <td>NIS</td>
      <td>:</td>
      <td>
        <input type="text" name="nis" value="<?= htmlspecialchars($data["nis"] ?? "") ?>" <?= $readonly ?? "" ?>
          required />
        <span class="required">*</span>
      </td>
    </tr>
    <tr>
      <td>Nama</td>
      <td>:</td>
      <td><input type="text" name="nama" class="input-wide" value="<?= htmlspecialchars($data["nama"] ?? "") ?>" />
      </td>
    </tr>
    <tr>
      <td>Kelas</td>
      <td>:</td>
      <td>
        <select name="kelas" class="input-small">
          <?php foreach (["X", "XI", "XII"] as $k): ?>
            <option value="<?= $k ?>" <?= ($data["kelas"] ?? "") === $k ? "selected" : "" ?>>
              <?= $k ?>
            </option>
          <?php endforeach; ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Tgl Lahir</td>
      <td>:</td>
      <td class="date-row">
        <input type="text" name="tgl" maxlength="2" placeholder="DD" class="date-input"
          value="<?= htmlspecialchars($ttlParts[2] ?? "") ?>" />
        /
        <input type="text" name="bln" maxlength="2" placeholder="MM" class="date-input"
          value="<?= htmlspecialchars($ttlParts[1] ?? "") ?>" />
        /
        <input type="text" name="thn" maxlength="4" placeholder="YYYY" class="year-input"
          value="<?= htmlspecialchars($ttlParts[0] ?? "") ?>" />
      </td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>:</td>
      <td><textarea name="alamat" class="input-wide" rows="3"><?= htmlspecialchars($data["alamat"] ?? "") ?></textarea>
      </td>
    </tr>
    <tr>
      <td>Kota</td>
      <td>:</td>
      <td><input type="text" name="kota" style="width:180px;" value="<?= htmlspecialchars($data["kota"] ?? "") ?>" />
      </td>
    </tr>
    <tr>
      <td>Jenis Kelamin</td>
      <td>:</td>
      <td class="radio-row">
        <label><input type="radio" name="jk" value="L" <?= ($data["jk"] ?? "") === "L" ? "checked" : "" ?> />
          Laki-Laki</label>
        <label><input type="radio" name="jk" value="P" <?= ($data["jk"] ?? "") === "P" ? "checked" : "" ?> />
          Perempuan</label>
      </td>
    </tr>
    <tr>
      <td>Hobby</td>
      <td>:</td>
      <td>
        <?php foreach ($hobbyList as $h): ?>
          <div><label>
              <input type="checkbox" name="hobi[]" value="<?= $h ?>" <?= in_array($h, $selectedHobi) ? "checked" : "" ?> />
              <?= $h ?>
            </label></div>
        <?php endforeach; ?>
      </td>
    </tr>
    <tr>
      <td>Pilihan Ekskul</td>
      <td>:</td>
      <td>
        <select name="ekskul[]" multiple size="8" class="input-listbox">
          <?php foreach ($ekskulList as $e): ?>
            <option value="<?= $e ?>" <?= in_array($e, $selectedEkskul) ? "selected" : "" ?>>
              <?= $e ?>
            </option>
          <?php endforeach; ?>
        </select>
      </td>
    </tr>
  </table>

  <div class="button-row">
    <button type="submit" class="button button-secondary">Kirim</button>
    <a href="index.php" class="button button-light">Cancel</a>
  </div>
  <p class="note">* Harus Di isi</p>
</div>