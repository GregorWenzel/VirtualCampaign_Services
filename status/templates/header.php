<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Filmproduction</title>

    <style type="text/css">
        @media screen {
            .main {
                background-color: #1d1d1d;
                color: #FFFFFF;
                font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;
                padding: 38px;
                margin: 0;
            }
            h1 {
                font-weight: 600;
                font-size: 3.9rem;
                margin: 4px 0 40px -20px;
            }
            h3 {
                font-weight: 400;
                font-size: 1.8rem;
                margin: 0 0 9px;
                letter-spacing: 0.05em;
            }
            p {
                font-size: 1.1rem;
                margin: 0 0 0.8em;
                line-height: 1.45em;
            }
            .status-table {
                font-size: 1.75rem;
                border-collapse: collapse;
                border: none;
                margin:0 0 75px;
                width: 100%;
                max-width: 710px;
            }
            .status-table td {
                vertical-align: top;
                padding: 3px 20px 8px 0;
            }
            .status-table td:first-child {
                width: 42%;
            }
            .normal {
                color: #29E214;
            }
            .unavailable {
                color: #FF2A44;
            }
            .productions-list {
                margin:0 0 40px;
                min-height: 300px;
            }
            .productions-list h3 {
                margin-bottom: 16px;
            }
            .productions-list table {
                width: 100%;
                max-width: 710px;
                border-collapse: collapse;
            }
            .productions-list th ,
            .productions-list td {
                vertical-align: top;
                text-align: left;
                font-weight: normal;
                padding: 3px 15px 3px 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                font-size: 1.1rem;
            }
            .productions-list td {
                border-top:1px solid #333;
            }
            .productions-list th:first-child ,
            .productions-list td:first-child {
                width: 157px;
            }
            .productions-list th:first-child + th ,
            .productions-list td:first-child + td {
                width: 250px;
            }
            .productions-list th:last-child ,
            .productions-list td:last-child {
                width:114px;
                padding-right: 0;
            }
        }

        @media screen and (max-width: 640px) {
            .main {
                padding: 10px;
            }
            h1 {
                margin-left: 0;
                font-size: 2rem;
            }
            h3 {
                font-size: 1.2rem;
            }
            .status-table {
                font-size: 1rem;
                margin-bottom: 30px;
            }
            .productions-list {
                min-height:100px;
            }
            .productions-list th, .productions-list td {
                white-space: normal;
                font-size: 0.7rem;
                width: auto !important;
                padding-right: 10px;
            }
            .productions-list td:first-child {
                word-break: keep-all;
            }
            .productions-list td {
                word-break: break-all;
            }
            .productions-list span {
                display: inline-block;
                white-space: nowrap;
                max-width: 30vw;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
        .bool-item {
            width:50px
        }
        .text-item {
            width:150px
        }
        .readonly {
            padding: 0 10px;
        }
    </style>
</head>

<body class="main">
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<div id="logo">
    <a href="http://virtualcampaign.de/"><img src="/img/logo.png" width="220" height="60" alt="virtualcampaign"/></a>
</div>
<div class="content">