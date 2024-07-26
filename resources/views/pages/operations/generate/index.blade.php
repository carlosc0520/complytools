@extends('layouts.app')

@section('title', 'ComplyTools')

@section('body')
  <div class="text-base my-4 breadcrumbs">
    <ul>
      <li class="text-conoce-green"><a href="{{ route('home') }}">Inicio</a></li>
      <li>Registro de Operaciones</li>
      <span id="userId" class="hidden">{{ $userId }}</span>
      <span id="operationId" class="hidden"></span>
    </ul>
  </div>

  @if(Session::has('success'))
    <div id="operationMassiveFailed" class="alert alert-success shadow-lg w-fit">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>{{ Session::get('success') }}</span>
      </div>
    </div>
  @endif

  @if(Session::has('fail'))
    <div id="operationMassiveFailed" class="alert alert-error shadow-lg w-fit">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>{{ Session::get('fail') }}</span>
      </div>
    </div>
  @endif

  <div class="card bg-base-100 shadow-md rounded-md my-4">
    <div class="bg-gray-200 py-2 px-4 flex justify-between">
      <span>Registro de Operaciones</span>
      <div>
        <button id="btnShowModalMassive" class="btn btn-sm btn-conoce negls--btn">
          Consulta Masiva
        </button>
        <a class="btn btn-sm border-none bg-conoce-green p-0 w-56" href="{{ route('operations') }}">Volver</a>
      </div>
    </div>

    <div class="operation--content">
      <!-- Begin - Form General -->
      <form id="operationForm1" class="operation--form">
        <h5 class="font-bold">SECCIÓN I - INFORMACIÓN DEL REGISTRO</h5>

        <div class="operation--row">
          <div class="operation--item">
            <div class="operation--item--span"><span>1. Empresa (*):</span></div>
            <div class="grid w-full">
              <input class="operation--item--input" name="company" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="operation--item">
            <div class="operation--item--span"><span>2. N° del registro (*):</span></div>
            <div class="grid w-full">
              <input class="operation--item--input" name="code" type="text" style="width: 100%" />
            </div>
          </div>
        </div>

        <div class="operation--row">
          <div class="operation--item">
            <div class="operation--item--span"><span>3. Oficina (*):</span></div>
            <div class="grid w-full">
              <input class="operation--item--input" name="office" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="operation--item">
            <div class="operation--item--span"><span>4. Fecha del registro:</span></div>
            <input class="operation--item--input" name="registeredAt" type="date" />
          </div>
        </div>

        <h5 class="font-bold">SECCIÓN II - IDENTIDAD DE LA PERSONA QUE FÍSICAMENTE REALIZA LA OPERACIÓN</h5>

        <div class="operation--row">
          <div class="operation--col">
            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>5. Apellidos:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="lastname1" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>6. Nombres:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="name1" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>8. Fecha de nacimiento:</span></div>
                <input class="operation--item--input" name="birthday1" type="date" />
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>9. Nacionalidad:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="nationality1" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>10. Profesión u ocupación:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="ocupation1" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>11. Domicilio:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="address1" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>12. Código Postal:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="postalcode1" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>13. Provincia:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="province1" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>14. Departamento:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="department1" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>15. País:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="country1" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>16. Teléfono:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="cellphone1" type="text" style="width: 100%" />
                </div>
              </div>
            </div>
          </div>

          <div class="operation--col">
            <div class="border border-solid border-black bg-white p-3">
              <label>7. Documento de Identidad</label>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>D.N.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="dni1" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.T.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="rut1" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>L.M.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="lm1" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>C.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ci1" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>C.E.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ce1" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>Pasaporte:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="passport1" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Emitido en:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="emittedAt1" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.C.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ruc1" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Otro:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="other1" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <h5 class="font-bold">SECCIÓN III - PERSONA EN CUYO NOMBRE SE REALIZA LA OPERACIÓN</h5>

        <div class="operation--row">
          <div class="operation--col">
            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>17. Apellidos:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="lastname2" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>18. Nombres:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="name2" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>20. Fecha de nacimiento:</span></div>
                <input class="operation--item--input" name="birthday2" type="date" />
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>21. Nacionalidad:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="nationality2" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>22. Profesión u ocupación:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="ocupation2" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>23. Domicilio:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="address2" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>24. Código Postal:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="postalcode2" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>25. Provincia:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="province2" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>26. Departamento:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="department2" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>27. País:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="country2" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>28. Teléfono:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="cellphone2" type="text" style="width: 100%" />
                </div>
              </div>
            </div>
          </div>

          <div class="operation--col">
            <div class="border border-solid border-black bg-white p-3">
              <label>19. Documento de Identidad</label>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>D.N.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="dni2" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.T.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="rut2" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>L.M.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="lm2" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>C.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ci2" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>C.E.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ce2" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>Pasaporte:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="passport2" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Emitido en:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="emittedAt2" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.C.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ruc2" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Otro:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="other2" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <h5 class="font-bold">SECCIÓN IV - PERSONA A FAVOR DE QUÉ SE REALIZA LA OPERACIÓN</h5>

        <div class="operation--row">
          <label class="text-sm">
            29. Si la operación fue realizada a favor de más de una persona indique y
            <a href="#" class="text-conoce-green font-bold">vaya a la Sección VI</a>
            (En esta sección se consignará la información del Beneficiario 1):
          </label>
          <input class="operation--item--input" name="beneficiary" type="text" style="width: 100%" />
        </div>

        <div class="operation--row">
          <div class="operation--col">
            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>30. Apellidos:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="lastname3" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>31. Nombres:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="name3" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>33. Fecha de nacimiento:</span></div>
                <input class="operation--item--input" name="birthday3" type="date" />
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>34. Nacionalidad:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="nationality3" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>35. Profesión u ocupación:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="ocupation3" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>36. Domicilio:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="address3" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>37. Código Postal:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="postalcode3" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>38. Provincia:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="province3" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>39. Departamento:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="department3" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>40. País:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="country3" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>41. Teléfono:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="cellphone3" type="text" style="width: 100%" />
                </div>
              </div>
            </div>
          </div>

          <div class="operation--col">
            <div class="border border-solid border-black bg-white p-3">
              <label>32. Documento de Identidad</label>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>D.N.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="dni3" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.T.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="rut3" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>L.M.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="lm3" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>C.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ci3" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>C.E.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ce3" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>Pasaporte:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="passport3" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Emitido en:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="emittedAt3" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.C.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ruc3" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Otro:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="other3" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <h5 class="font-bold">SECCIÓN V - DESCRIPCIÓN DE LA OPERACIÓN</h5>

        <div class="operation--row--3">
          <div class="operation--item">
            <div class="operation--item--span"><span>42. Monto de la operación (US$):</span></div>
            <div class="grid w-full">
              <input class="operation--item--input" name="amount" type="text" style="width: 100%" />
            </div>
          </div>

          <div class="operation--item">
            <div class="operation--item--span"><span>43. Fecha de la operación:</span></div>
            <input class="operation--item--input" name="date" type="date" />
          </div>

          <div class="operation--item">
            <div class="operation--item--span"><span>44. Lugar de realización:</span></div>
            <div class="grid w-full">
              <input class="operation--item--input" name="location" type="text" style="width: 100%" />
            </div>
          </div>
        </div>

        <label>45. Modalidad de pago (en US$):</label>

        <div class="operation--col--options">
          <div class="operation--item--option">
            <span>a. Moneda Nacional:</span>
            <input class="operation--item--input" name="nationalcurrency" type="text" />
          </div>

          <div class="operation--row">
            <div class="operation--item--option">
              <span>b. Moneda Extranjera:</span>
              <input class="operation--item--input" name="foreigncurrency" type="text" />
            </div>

            <div class="operation--item--option">
              <span>Especificar moneda:</span>
              <input class="operation--item--input" name="foreigncurrencyDetails" type="text" />
            </div>
          </div>

          <div class="operation--item--option">
            <span>c. Cheque de gerencia:</span>
            <input class="operation--item--input" name="cashierscheck" type="text" />
          </div>

          <div class="operation--item--option">
            <span>d. Cheque de viajero:</span>
            <input class="operation--item--input" name="travelerscheck" type="text" />
          </div>

          <div class="operation--item--option">
            <span>e. Órdenes de pago:</span>
            <input class="operation--item--input" name="paymentorder" type="text" />
          </div>

          <div class="operation--row">
            <div class="operation--item--option">
              <span>f. Otros:</span>
              <input class="operation--item--input" name="otherp" type="text" />
            </div>

            <div class="operation--item--option">
              <span>Especificar:</span>
              <input class="operation--item--input" name="otherDetailsp" type="text" />
            </div>
          </div>
        </div>

        <label>46. Tipo de operación:</label>

        <div class="operation--list">
          <div class="operation--item--option">
            <span>a. Compra de valores:</span>
            <input class="operation--item--input" name="buy" type="text" />
          </div>

          <div class="operation--item--option">
            <span>b. Venta de valores:</span>
            <input class="operation--item--input" name="sell" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>c. Asesorías:</span>
            <input class="operation--item--input" name="consultancies" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>d. Colocaciones primarias:</span>
            <input class="operation--item--input" name="primaryplacements" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>e. Administración de cartera:</span>
            <input class="operation--item--input" name="portfoliomanagement" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>f. Custodia de valores:</span>
            <input class="operation--item--input" name="custody" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>g. Mutuos de dinero:</span>
            <input class="operation--item--input" name="mutualmoney" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>h. Préstamo de valores:</span>
            <input class="operation--item--input" name="loan" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>i. Fondos mutuos:</span>
            <input class="operation--item--input" name="mutualfunds" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>j. Fondos de inversión:</span>
            <input class="operation--item--input" name="investmentfunds" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>k. Derivados:</span>
            <input class="operation--item--input" name="derivatives" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>l. Fondos colectivos:</span>
            <input class="operation--item--input" name="collectivefunds" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>m. Otros:</span>
            <input class="operation--item--input" name="otherq" type="text"  />
          </div>

          <div class="operation--item--option">
            <span>Especificar:</span>
            <input class="operation--item--input" name="otherDetailsq" type="text"  />
          </div>
        </div>

        <label>47. Indicar números de cuentas utilizadas:</label>

        <div class="operation--row--3">
          <div class="operation--item">
            <input class="operation--item--input" name="account1" type="text" style="width: 100%" />
          </div>
          <div class="operation--item">
            <input class="operation--item--input" name="account2" type="text" style="width: 100%" />
          </div>
          <div class="operation--item">
            <input class="operation--item--input" name="account3" type="text" style="width: 100%" />
          </div>
        </div>
      </form>
      <!-- End - Form General -->

      <!-- Begin - Form People -->
      <form id="operationForm2" class="operation--form my-4">
        <div class="grid">
          <h5 class="font-bold">SECCIÓN VI - BENEFICIARIOS MÚLTIPLES</h5>  
          <div class="overflow-x-auto">
            <table class="table-conoce">
              <thead>
                <tr>
                  <th title="N° BENEFICIARIO">N° BENEFICIARIO</th>
                  <th title="APELLIDOS O RAZÓN SOCIAL">APELLIDOS O RAZÓN SOCIAL</th>
                  <th title="NOMBRES">NOMBRES</th>
                  <th title="FECHA DE NACIMIENTOS">FECHA DE NACIMIENTO</th>
                  <th title="ACCIONES">ACCIONES</th>
                </tr>
              </thead>
              <tbody id="operation-table-people">
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex justify-end my-2">
          <label for="modal-operation-people" class="modal-button btn btn-sm border-none bg-conoce-green p-0 w-44">
            + Nueva Persona
          </label>
        </div>
      </form>
      <!-- End - Form People -->

      <div class="actions">
        <button id="btnOperationSave" class="btn btn-sm btn-warning">Guardar</button>
        <button id="btnOperationRegister"  class="btn btn-sm bg-conoce-green">Registrar</button>
      </div>
    </div>
  </div>

  <!-- Begin - Modal Details -->
  <label for="modal-operation-people" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-operation-people" class="modal-toggle" />
  <div class="modal">
    <div id="operation--modal--people" class="modal-box relative p-0 w-11/12 max-w-5xl">
      <div class="h-8 bg-modal" style="padding: 5px 15px;">
        <h5 class="font-bold" style="color: white">BENEFICIARIO</h5>
      </div>

      <div class="bg-white p-3 operation--modal--content">
        <div class="operation--row">
          <div class="operation--col">
            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>Apellido o razón social:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="lastname__" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>Nombres:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="name__" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>Fecha de nacimiento:</span></div>
                <input class="operation--item--input" name="birthday__" type="date" />
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>Nacionalidad:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="nationality__" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>Profesión u ocupación:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="ocupation__" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>Teléfono:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="cellphone__" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>Código Postal:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="postalcode__" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>Provincia:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="province__" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>Departamento:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="department__" type="text" style="width: 100%" />
                </div>
              </div>

              <div class="operation--item">
                <div class="operation--item--span"><span>País:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="country__" type="text" style="width: 100%" />
                </div>
              </div>
            </div>

            <div class="operation--row">
              <div class="operation--item">
                <div class="operation--item--span"><span>Dirección:</span></div>
                <div class="grid w-full">
                  <input class="operation--item--input" name="address__" type="text" style="width: 100%" />
                </div>
              </div>
            </div>
          </div>

          <div class="operation--col">
            <div class="border border-solid border-black bg-white p-3">
              <label>Documento de Identidad</label>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>D.N.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="dni__" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.T.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="rut__" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>L.M.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="lm__" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>C.I.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ci__" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>C.E.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ce__" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>Pasaporte:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="passport__" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Emitido en:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="emittedAt__" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>

              <div class="operation--row">
                <div class="operation--item">
                  <div class="operation--item--span"><span>R.U.C.</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="ruc__" type="text" style="width: 100%" />
                  </div>
                </div>

                <div class="operation--item">
                  <div class="operation--item--span"><span>Otro:</span></div>
                  <div class="grid w-full">
                    <input class="operation--item--input" name="other__" type="text" style="width: 100%" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-action justify-end py-2 px-4">
        <label for="modal-operation-people" class="btn bg-red-600 btn-sm border-none">Cerrar</label>
        <button id="reportBtnSavePeople" class="btn bg-conoce-green btn-sm border-none">Guardar</button>
      </div>
    </div>
  </div>
  <!-- End - Modal Details -->

  <!-- Begin - Modal Massive -->
  <label id="operation-massive" for="modal-operation-massive" class="btn modal-button hidden">open modal</label>
  <input type="checkbox" id="modal-operation-massive" class="modal-toggle" />
  <div class="modal">
    <div id="neglst--modal--massive" class="modal-box modal-alert relative p-0">
      <div class="bg-modal h-8">
      </div>

      <div class="bg-white p-4">
        <span class="font-bold">IMPORTAR REGISTRO OPERACIONES:</span>
      </div>

      <form class="flex justify-center" action="/operations/massive" method="POST" enctype="multipart/form-data">
        @csrf

        <button id="btnUploadMassive" type="button" class="btn bg-conoce-green border-none">
          <input
            id="fileMassive"
            name="file"
            class="form-control hidden"
            type="file"
            multiple=""
            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
          >
          <input id="idUser" name="idUser" type="text" class="hidden" value="{{ $userId }}" />
          Subir Archivo
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
          </svg>
          <label id="lbFileMassive" for="fileMassive" class="hidden"></label>
        </button>

        <div id="previewMassive" class="flex flex-col justify-center items-center">
          <img src="{{ asset('assets/icons/excel.png') }}" height="129" width="135" />
          <span id="previewFileMassive"></span>
          <span class="font-bold mt-4">Importar registros:</span>
          <div>
            <button id="btnMassive" class="btn btn-sm" type="submit">Importar</button>
          </div>
        </div>
      </form>

      <div class="modal-action justify-end py-2 px-4 bg-modal">
        <label for="modal-operation-massive" class="btn bg-red-600 btn-sm border-none">Cerrar</label>
      </div>
    </div>
  </div>
  <!-- End - Modal Massive -->
@endsection

@push('js')
  <script src="{{ asset('js/operations-generate.js') }}" type="text/javascript"></script>
@endpush