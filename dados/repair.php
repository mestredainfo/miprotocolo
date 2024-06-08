<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

if (file_exists(miUserPath() . '/.miprotocolo/dados/createdb.txt')) {
	unlink(miUserPath() . '/.miprotocolo/dados/createdb.txt');
}
miRedirect('/protocolos/list.php');
