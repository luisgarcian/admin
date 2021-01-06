<?php
	class conexion{
		private $servidor;
                private $basedatos;		
                private $usuario;
                private $contrasena;
		private $driver ;
		
		public  $conexion;
		public  $num_rows = 0;
		private $result;
                private $seldb = false;

		public function __construct($tipo)
		{
                        if ($tipo == 'l') {
                        $local  = array ( "ZTSISPC03-PC\SQLEXPRESS", "Sirco", "", "", "{SQL Server}" );
			list($this->servidor, $this->basedatos, $this->usuario, $this->contrasena, $this->driver) 
			= $local;
                        
                        }
                        if ($tipo == 'r') {
                         $remoto = array ( "zapateriastorreon.database.windows.net", "SircoDWH", "pappos",  "SirCo_33", "" );
                         list($this->servidor, $this->basedatos, $this->usuario, $this->contrasena, $this->driver) 
                         = $remoto;
                        
                        }
	        }	
		
                 function conectar() {

                        try {
                             // Returns a MS SQL link identifier on success, or FALSE on error.
			     $this->conexion = mssql_connect( $this->servidor, $this->usuario, $this->contrasena);

                             // Sets the current active database on the server that's associated 
                             // with the specified link identifier.
                             // Devuelve TRUE en caso de éxito o FALSE en caso de error.
                             $sel_db = mssql_select_db($basedatos, $this->conexion);

			}catch (Exception $e) 
                        {
	                   $this->conexion = 'Error de conexión' ;
      	                   echo "ERROR: ". $e->getMessage();
	                }	   
                }
		
		function query($sql) {

                        // Comprueba si fue posible seleccionar BDatos en conexion
                        if ($this->sel_db) {
                            // Returns a MS SQL result resource on success, 
                            // TRUE if no rows were returned, or FALSE on error.

         		    $this->result    = mssql_query( $sql, $this->conexion );

                            //Returns the number of rows, as an integer.
	         	    $this->num_rows  = mssql_num_rows($this->result);
                        } else {
                           $this->conexion = 'No se encontró B. Datos';
                        }
			return $this->result;
		}


		public function fetch_row() {
			if ($this->num_rows >0) {
                           //Returns an array that corresponds to the fetched row, or FALSE if there are no more rows.
                           return(mssql_fetch_row($this->result));
			}
		}

		public function result($row, $campo) {
                     //returns the contents of one cell from a MS SQL result set
                    return(mssql_result($this->result, $row, $campo));
		}

                function cerrar() {
		  mssql_close ( $this->conexion );
                }
	
	}
?>
