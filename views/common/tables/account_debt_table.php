<table class="col-12 col-md-10 table table-bordered border border-black text-center h6">
    <thead>
        <tr class="bg-theme text-white fw-bold">
            <th>Deuda</th>
            <th>Bolívares</th>
            <th>Dólares</th>
        </tr>
    </thead>
    <tbody id="debt-table">
        <tr>
            <td class="p-1 border border-black bg-theme text-white fw-bold align-middle">Mensualidad</td>
            <td class="p-1 border border-black align-middle text-<?= $debtState['months']['total'] > 0 ? 'danger' : 'success' ?>">
                <div class="d-flex justify-content-center flex-wrap">
                    <?php if($debtState['months']['total'] > 0) { ?>
                        <?php foreach($debtState['months']['detail'] as $month => $debt) { ?>
                            <span class="col-6 p-0"><?= $month ?></span>
                            <span class="col-6 p-0">Bs. <?= GetPrettyCiphers($debt * $usd['price']) ?></span>
                        <?php } ?>
                    <?php } else { ?>
                        SIN DEUDA
                    <?php } ?>
                </div>
            </td>
            <td class="p-1 border border-black align-middle text-<?= $debtState['months']['total'] > 0 ? 'danger' : 'success' ?>">
                <div class="d-flex justify-content-center flex-wrap">
                    <?php if($debtState['months']['total'] > 0) { ?>
                        <?php foreach($debtState['months']['detail'] as $month => $debt) { ?>
                            <span class="col-6 p-0"><?= $month ?></span>
                            <span class="col-6 p-0"><?= GetPrettyCiphers($debt) ?>$</span>
                        <?php } ?>
                    <?php } else { ?>
                        SIN DEUDA
                    <?php } ?>
                </div>
            </td>
        </tr>

        <tr>
            <td class="p-1 border border-black bg-theme text-white fw-bold align-middle">Mora</td>
            <td class="p-1 border border-black align-middle text-<?= $debtState['retard']['total'] > 0 ? 'danger' : 'success' ?>">
                <div class="d-flex justify-content-center flex-wrap">
                    <?php if($debtState['retard']['total'] > 0) { ?>
                        <?php foreach($debtState['retard']['detail'] as $month => $debt) { ?>
                            <span class="col-6 p-0"><?= $month ?></span>
                            <span class="col-6 p-0">Bs. <?= GetPrettyCiphers($debt * $usd['price']) ?></span>
                        <?php } ?>
                    <?php } else { ?>
                        SIN DEUDA
                    <?php } ?>
                </div>
            </td>
            <td class="p-1 border border-black align-middle text-<?= $debtState['retard']['total'] > 0 ? 'danger' : 'success' ?>">
                <div class="d-flex justify-content-center flex-wrap">
                    <?php if($debtState['retard']['total'] > 0) { ?>
                        <?php foreach($debtState['retard']['detail'] as $month => $debt) { ?>
                            <span class="col-6 p-0"><?= $month ?></span>
                            <span class="col-6 p-0"><?= GetPrettyCiphers($debt) ?>$</span>
                        <?php } ?>
                    <?php } else { ?>
                        SIN DEUDA
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
        <td class="p-1 border border-black bg-theme text-white fw-bold align-middle">FOC</td>
            <?php if($debtState['foc']) { ?> 
                <td class="p-1 border border-black align-middle text-success align-middle" colspan="2">SIN DEUDA</td>
            <?php } else { ?>
                <td class="p-1 border border-black align-middle text-danger">Bs. <?= GetPrettyCiphers($focProduct['price'] * $usd['price']) ?></td>
                <td class="p-1 border border-black align-middle text-danger"><?= GetPrettyCiphers($focProduct['price']) ?>$</td>
            <?php } ?>
        </tr>
        <tr>
            <td class="p-1 border border-black bg-theme text-white fw-bold align-middle">TOTAL</td>
            <?php if($total_debt > 0) { ?>                                
                <td class="p-1 border border-black align-middle fw-bold text-danger">Bs. <?= GetPrettyCiphers($total_debt * $usd['price']) ?></td>
                <td class="p-1 border border-black align-middle fw-bold text-danger"><?= GetPrettyCiphers($total_debt) ?>$</td>
            <?php } else { ?>
                <td class="p-1 border border-black align-middle fw-bold text-success" colspan="2">SIN DEUDA</td>
            <?php } ?>
        </tr>
    </tbody>
</table>